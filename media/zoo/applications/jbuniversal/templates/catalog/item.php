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

$this->app->jbdebug->mark('template::item::start');

$this->app->jblayout->setView($this);

$this->app->jbwrapper->start();

// render item
if (!$this->app->jbcache->start(array($this->item->modified, $this->item->id))) {
    echo $this->app->jblayout->renderItem($this->item, 'full');
    $this->app->jbcache->stop();
}

// render comments (if no rendered in element)
if (!defined('JBZOO_COMMENTS_RENDERED')) {
    echo $this->app->comment->renderComments($this, $this->item);
}

$this->app->jbwrapper->end();

$this->app->jbdebug->mark('template::item::finish');
