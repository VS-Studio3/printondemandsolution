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

// remove empty categories
if ($vars['params']->get('template.subcategory_items', 0)) {
    $objects = array();
    foreach ($vars['objects'] as $category) {
        if ($category->itemCount()) { //
            $objects[] = $category;
        }
    }

} else {
    $objects = $vars['objects'];

}

if ($this->app->request->get('view', false) == 'frontpage') {
    echo '<h2 class="jbzoo-subtitle">' . JText::_('JBZOO_CATEGORIES') . '</h2>';
}

echo $this->columns('subcategory', $objects);
