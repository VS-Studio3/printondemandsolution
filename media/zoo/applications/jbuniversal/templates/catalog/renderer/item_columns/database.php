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

echo $this->app->jbjoomla->renderPosition('jbzoo_database_filter');

if ($vars['count']) : ?>

    <table class="jsTableSorter tablesorter zebra">
        <caption>Сравнительная таблица</caption>

        <thead>
        <tr>
            <th>Категория</th>
            <th>Форма</th>
            <th>Марка</th>
            <th>Цвет</th>
            <th>Разм.&nbsp;&nbsp;&nbsp;</th>
            <th>Тип&nbsp;&nbsp;&nbsp;</th>
            <th>Шт.&nbsp;&nbsp;&nbsp;</th>
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
