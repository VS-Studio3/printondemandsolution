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

$this->app->jbdebug->mark('template::tag::start');

$this->app->jblayout->setView($this);

if (!$this->app->jbcache->start($this->tag)) {
    $this->app->jbwrapper->start();

    ?><h1 class="title"><?php echo JText::_('JBZOO_ARTICLES_TAGGED_WITH') . ': ' . $this->tag; ?></h1><?php

    // items
    if (count($this->items) > 0) {
        echo $this->app->jblayout->render('items', $this->items);
    }

    // pagination render
    echo $this->app->jblayout->render('pagination', $this->pagination, array('link' => $this->pagination_link));

    $this->app->jbwrapper->end();
    $this->app->jbcache->stop();
}

$this->app->jbdebug->mark('template::tag::finish');