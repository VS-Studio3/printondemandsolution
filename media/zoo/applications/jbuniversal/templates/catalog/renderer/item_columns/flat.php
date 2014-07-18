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

$this->app->jbassets->tablesorter();

if ($vars['count']) : ?>
    <table class="jsTableSorter tablesorter">
        <caption>В продаже квартиры и дома</caption>
        <thead>
        <tr>
            <th>Фото</th>
            <th>Улица</th>
            <th>Район</th>
            <th>Площадь</th>
            <th>Тип</th>
            <th>Этаж</th>
            <th>*</th>
        </tr>
        </thead>
        <tbody>
            <?php
            foreach ($vars['objects'] as $object) :
                echo $object;
            endforeach;
            ?>
        </tbody>
    </table>

    <script type="text/javascript">
        jQuery(function ($) {
            $('.jsTableSorter').tablesorter({});
        });
    </script>

<?php endif;
