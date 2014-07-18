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

$this->app->jbassets->heightFix();

$count = count($items);

if ($count) {

    echo '<div class="related-items related-items-col-' . $columns . '">';

    $j = 0;
    foreach ($items as $item) {

        $first = ($j == 0) ? ' first' : '';
        $last  = ($j == $count - 1) ? ' last' : '';
        $j++;

        $isLast = $j % $columns == 0;

        if ($isLast) {
            $last .= ' last';
        }

        echo '<div class="rborder column width' . intval(100 / $columns) . $first . $last . '">' . $item . '</div>';

        if ($isLast) {
            echo '<div class="clear clr"></div>';
        }
    }

    echo '<div class="clear clr"></div>';
    echo '</div>';

}
