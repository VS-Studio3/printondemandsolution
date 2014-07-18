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


class JBNotifyHelper extends AppHelper
{
    /**
     * Show warning message
     * @param $message string
     * @return mixed
     */
    public function warning($message)
    {
        return $this->app->error->raiseWarning(0, $message);
    }

    /**
     * Show warning message
     * @param $message string
     * @return mixed
     */
    public function notice($message)
    {
        return $this->app->error->raiseNotice(0, $message);
    }

    /**
     * Show error message
     * @param $message
     * @return mixed
     */
    public function error($message)
    {
        return $this->app->error->raiseError(500, $message);
    }

}
