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

class CompareJBUniversalController extends BaseJBUniversalController {
    /**
     * Filter action
     * @throws CompareJBUniversalControllerException
     * @return void
     */
    function compare()
    {
        // init
        $this->app->jbdebug->mark('compare::init');
        $this->_init();

        $type   = $this->_jbreq->get('type');
        $appId  = $this->_jbreq->get('app_id');
        $itemId = $this->_jbreq->get('Itemid');
        $layout = $this->_jbreq->get('layout', 'v');

        if (!$type || !$appId) {
            throw new CompareJBUniversalControllerException('Type or AppId is no set');
        }

        // get items
        $searchModel = JBModelFilter::model();
        $itemIds     = $this->app->jbcompare->getItemsByType($type);
        $items       = $searchModel->getZooItemsByIds($itemIds);

        $this->items      = $items;
        $this->params     = $this->_params;
        $this->itemType   = $type;
        $this->appId      = $appId;
        $this->layoutType = $layout;
        $this->itemId     = $itemId;

        if (!$this->template = $this->application->getTemplate()) {
            $this->app->error->raiseError(500, JText::_('No template selected'));
            return;
        }

        // set renderer
        $this->renderer = $this->app->renderer->create('compare')->addPath(
            array(
                $this->app->path->path('component.site:'),
                $this->template->getPath()
            )
        );

        $this->app->jbdebug->mark('compare::renderInit');

        // display view
        $this->getView('compare')->addTemplatePath($this->template->getPath())->setLayout('compare')->display();

        $this->app->jbdebug->mark('compare::display');
    }

    /**
     * Clear action
     */
    public function clear()
    {
        $this->_init();
        $this->app->jbcompare->removeItems();

        $type   = $this->_jbreq->get('type');
        $appId  = $this->_jbreq->get('app_id');
        $itemId = $this->_jbreq->get('back_itemid');

        $compareUrl = $this->app->jbrouter->compare($itemId, 'v', $type, $appId);

        JFactory::getApplication()->redirect($compareUrl, JText::_('JBZOO_COMPARE_CLEAR'));
    }

}

class CompareJBUniversalControllerException extends AppException {
}
