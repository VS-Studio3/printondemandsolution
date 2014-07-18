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

class JBEventItem extends JBEvent
{

    public static function init($event)
    {
    }

    /**
     * Item saved event
     * @static
     * @param $event AppEvent
     */
    public static function saved($event)
    {
        $item = $event->getSubject();
        JBModelSearchindex::model()->updateByItemId($item);
    }

    /**
     * Item deleted event
     * @static
     * @param $event AppEvent
     */
    public static function deleted($event)
    {
        $item = $event->getSubject();
        JBModelSearchindex::model()->removeById($item);
    }

    public static function stateChanged($event)
    {
    }

    public static function beforeDisplay($event)
    {
    }

    public static function afterDisplay($event)
    {
    }
}