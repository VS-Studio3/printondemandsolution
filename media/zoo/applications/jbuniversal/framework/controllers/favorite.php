<?php
/**
 * JBZoo is universal CCK based Joomla! CMS and YooTheme Zoo component
 * @category   JBZoo
 * @author     smet.denis <admin@joomla-book.ru>
 * @copyright  Copyright (c) 2009-2012, Joomla-book.ru
 * @license    http://joomla-book.ru/info/disclaimer
 * @link       http://joomla-book.ru/projects/jbzoo JBZoo project page
 */
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/base.php';

class FavoriteJBUniversalController extends BaseJBUniversalController
{
    /**
     * Favorite list of curret user
     * @throws BasketJBUniversalControllerException
     * @throws FavoriteJBUniversalControllerException
     */
    function favorite()
    {
        // init
        $this->app->jbdebug->mark('favorite::init');
        $this->_init('favorite');

        $type   = $this->_jbreq->get('type');
        $appId  = $this->_jbreq->get('app_id');
        $itemId = $this->_jbreq->get('Itemid');

        if (!$appId) {
            throw new FavoriteJBUniversalControllerException('Type or AppId is no set');
        }

        if (!JFactory::getUser()->id) {
            JError::raiseNotice(0, JText::_('JBZOO_FAVORITE_NOTAUTH_NOTICE'));
        }

        // get items
        $searchModel = JBModelFilter::model();
        $items       = $this->app->jbfavorite->getAllItems();

        $items        = $searchModel->getZooItemsByIds(array_keys($items));
        $this->items  = $items;
        $this->params = $this->_params;
        $this->appId  = $appId;
        $this->itemId = $itemId;

        if (!$this->template = $this->application->getTemplate()) {
            throw new FavoriteJBUniversalControllerException('No template selected');
        }

        // set renderer
        $this->renderer = $this->app->renderer->create('item')->addPath(
            array(
                $this->app->path->path('component.site:'),
                $this->template->getPath()
            )
        );

        $this->app->jbdebug->mark('favorite::renderInit');

        // display view
        $this->getView('favorite')->addTemplatePath($this->template->getPath())->setLayout('favorite')->display();

        $this->app->jbdebug->mark('favorite::display');
    }

    /**
     * Clear action
     */
    public function remove()
    {
        $this->_init('favorite');

        $itemId = (int)$this->_jbreq->get('item_id');
        $item   = $this->app->table->item->get($itemId);

        $this->app->jbfavorite->toggleState($item);

        $this->app->jbajax->send();
    }

}

class FavoriteJBUniversalControllerException extends AppException
{
}
