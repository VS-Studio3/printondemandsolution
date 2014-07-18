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


if ($this->app->request->get('view', false) == 'frontpage'
    && $this->app->request->get('task', false) != 'filter'
) {
    echo '<h2 class="jbzoo-subtitle">' . JText::_('JBZOO_ITEMS_TOP') . '</h2>';
}

echo $this->columns('item', $vars['objects'], true);
