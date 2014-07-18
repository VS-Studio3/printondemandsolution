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

$this->app->jbdebug->mark('layout::subcategory_columns::start');

if ($vars['count']) {

    $count = $vars['count'];

    echo '<div class="subcategories subcategory-col-' . $vars['cols_num'] . '">';

    $j = 0;
    foreach ($vars['objects'] as $object) {

        $first = ($j == 0) ? ' first' : '';
        $last = ($j == $count - 1) ? ' last' : '';
        $j++;

        $isLast = $j % $vars['cols_num'] == 0 && $vars['cols_order'] == 0;

        if ($isLast) {
            $last .= ' last';
        }

        echo'<div class="rborder column width' . intval(100 / $vars['cols_num']) . $first . $last . '">' . $object
            . '</div>';

        if ($isLast) {
            echo '<div class="clear clr"></div>';
        }
    }

    echo '<div class="clear clr"></div>';
    echo '</div>';

}

$this->app->jbdebug->mark('layout::subcategory_columns::finish');
