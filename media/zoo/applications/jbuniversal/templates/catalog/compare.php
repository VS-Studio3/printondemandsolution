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

$this->app->jbdebug->mark('template::compare::start');
$this->app->jblayout->setView($this);
$this->app->document->setTitle(JText::_('JBZOO_COMPARE_ITEMS'));
$this->app->jbwrapper->start();

?><h1 class="title"><?php echo JText::_('JBZOO_COMPARE_ITEMS');?></h1><?php

if (!empty($this->items)) {
    // items
    echo $this->app->jblayout->render('compare', $this->items);

} else {
    echo '<p>' . JText::_('JBZOO_COMPARE_ITEMS_NOT_FOUND') . '</p>';
}

$this->app->jbwrapper->end();
$this->app->jbdebug->mark('template::compare::finish');
