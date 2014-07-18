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


class JBDebugHelper extends AppHelper
{

    /**
     * JBDump instance
     * @var JBDump|null
     */
    protected static $_jbdump = null;

    /**
     * JBDump context
     * @var string
     */
    protected $_jbdumpContext = 'jbzoo';

    /**
     * JBDump params
     * @var array
     */
    protected $_jbdumpParams = array();

    /**
     * @param Application $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        if (JFactory::getApplication()->isSite() && self::$_jbdump === null) {

            if (class_exists('jbdump')) {
                // jbdump plugin
                self::$_jbdump = JBDump::i($this->_jbdumpParams);
            }
        }

    }

    /**
     * Set profiler mark
     * @param string $name
     */
    public function mark($name = '')
    {
        return; // for debug only

        if (self::$_jbdump !== null) {
            self::$_jbdump->mark($name);
        }
    }

    /**
     * Dump sql queries
     * @param $select
     */
    public function sql($select)
    {
        return; // for debug only

        if (self::$_jbdump !== null) {

            $select = (string)$select;
            self::$_jbdump->dump((string)$select, 'jbdebug::sql');
        }
    }
}
