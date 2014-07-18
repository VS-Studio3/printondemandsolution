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

$this->app->jbdebug->mark('template::_comments::start');

echo $this->app->jblayout->render(
    'comments', $comments, array(
        'active_author' => $active_author,
        'comments'      => $comments,
        'captcha'       => $captcha,
        'params'        => $params,
        'item'          => $item,
    )
);

$this->app->jbdebug->mark('template::_comments::finish');