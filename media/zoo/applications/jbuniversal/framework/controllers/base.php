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


require_once JPATH_COMPONENT . '/controllers/default.php';

class BaseJBUniversalController extends DefaultController
{

    /**
     * @var JBRequestHelper
     */
    protected $_jbreq = null;

    /**
     * @var RequestHelper|JRequest
     */
    protected $_req = null;

    /**
     * @var DatabaseHelper|JDatabaseMySQLi
     */
    protected $_db = null;

    /**
     * @var JString
     */
    protected $_str = null;

    /**
     * @var ParameterData
     */
    protected $_params = null;

    /**
     * Init controler vars
     * @param string $types
     */
    protected function _init($types = '')
    {
        $this->_db     = $this->app->database;
        $this->_req    = $this->app->request;
        $this->_jbreq  = $this->app->jbrequest;
        $this->_str    = $this->app->string;
        $this->_params = $this->application->getParams('frontpage');
    }

}

