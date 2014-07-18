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

$this->app->jbdebug->mark('template::payment_fail::start');
$this->app->jblayout->setView($this);
$this->app->document->setTitle(JText::_('JBZOO_PAYMENT_FAIL_PAGE_TITLE'));
$this->app->jbwrapper->start();

$user = JFactory::getUser();

?><h1 class="title"><?php echo JText::_('JBZOO_PAYMENT_FAIL_PAGE_TITLE');?></h1><?php


echo $this->app->jblayout->render('payment_fail');


$this->app->jbwrapper->end();
$this->app->jbdebug->mark('template::payment_fail::finish');
