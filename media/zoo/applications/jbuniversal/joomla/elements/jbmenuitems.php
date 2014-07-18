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

if ($app->jbversion->joomla('3')) {
    echo App::getInstance('zoo')->jbfield->menuitems_j3($name, $value, $control_name, $node, $parent);
} else {
    echo App::getInstance('zoo')->jbfield->menuitems_j25($name, $value, $control_name, $node, $parent);
}
