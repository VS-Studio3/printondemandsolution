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

class SearchJBUniversalController extends BaseJBUniversalController
{

    /**
     * Filter action
     * @return void
     */
    function filter()
    {
        $this->app->jbdebug->mark('filter::init');
        $this->_init();

        $type          = $this->_jbreq->get('type');
        $page          = ($page = $this->_jbreq->get('page', 1)) ? $page : 1;
        $logic         = $this->_str->strtoupper($this->_jbreq->get('logic', 'and'));
        $order         = $this->_jbreq->get('order', 'none');
        $exact         = (int)$this->_jbreq->get('exact', 0);
        $limit         = $this->_jbreq->get('limit', $this->_params->get('config.items_per_page', 2));
        $offset        = $limit * ($page - 1);
        $elements      = $this->_jbreq->getElements();
        $applicationId = $this->_jbreq->get('app_id');

        // search!
        $searchModel = JBModelFilter::model();
        $itemsIds    = $searchModel->search($elements, $logic, $type, $applicationId, $exact, $offset, $limit, $order);
        $itemsCount  = $searchModel->searchCount($elements, $logic, $type, $applicationId, $exact);
        $items       = $searchModel->getZooItemsByIds($itemsIds, $order);

        // create pagination
        if ($this->_jbreq->isPost()) {
            unset($_POST['page']);
            unset($_POST['view']);
            unset($_POST['layout']);
            $this->pagination_link = 'index.php?' . http_build_query($_POST);

        } else {
            unset($_GET['page']);
            unset($_GET['view']);
            unset($_GET['layout']);
            $this->pagination_link = 'index.php?' . http_build_query($_GET);

        }
        $this->pagination = $this->app->pagination->create($itemsCount, $page, $limit, 'page', 'app');
        $this->pagination->setShowAll($limit == 0);
        $this->app->jbdebug->mark('filter::pagination');

        // set template and params
        if (!$this->template = $this->application->getTemplate()) {
            $this->app->error->raiseError(500, JText::_('No template selected'));
            return;
        }

        // assign variables
        $this->items      = $items;
        $this->params     = $this->_params;
        $this->itemsCount = $itemsCount;

        // set renderer
        $this->renderer = $this->app->renderer->create('item')->addPath(
            array(
                $this->app->path->path('component.site:'),
                $this->template->getPath()
            )
        );
        $this->app->jbdebug->mark('filter::renderInit');

        // display view
        $this->getView('filter')->addTemplatePath($this->template->getPath())->setLayout('filter')->display();

        $this->app->jbdebug->mark('filter::display');
    }

}

/**
 *  Class: SearchUniversalControllerException
 */
class SearchJBUniversalControllerException extends AppException
{
}
