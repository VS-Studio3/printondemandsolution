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

$this->app->jbdebug->mark('template::filter::start');

$this->app->jblayout->setView($this);

if (!$this->app->jbcache->start()) {
    $this->app->jbwrapper->start();

    ?><h1 class="title"><?php echo JText::_('JBZOO_SEARCH_RESULT');?></h1><?php

    if ($this->items) {

        echo '<p>' . JText::_('JBZOO_FILTER_TOTAL_RESULT') . ': ' . $this->itemsCount . '</p>';

        // items
        echo $this->app->jblayout->render('items', $this->items);

        // pagination render
        echo $this->app->jblayout->render('pagination', $this->pagination, array('link' => $this->pagination_link));

    } else {
        echo $this->app->jbjoomla->renderPosition('jbzoo_price_filter');
        ?><p><?php echo JText::_('JBZOO_FILTER_ITEMS_NOT_FOUND');?></p><?php

    }

    $this->app->jbwrapper->end();
    $this->app->jbcache->stop();
}

$this->app->jbdebug->mark('template::filter::finish');
