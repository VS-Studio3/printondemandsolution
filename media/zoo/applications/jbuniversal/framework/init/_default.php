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

$app = App::getInstance('zoo');
if (!$app->jbenv->isSite()
    && $app->jbrequest->is('group', JBZOO_APP_GROUP)
    && $app->jbrequest->is('controller', 'manager')
) {
    $app->jbupdate->check();
}
