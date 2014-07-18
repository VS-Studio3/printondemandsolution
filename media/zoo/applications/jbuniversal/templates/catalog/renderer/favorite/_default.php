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

$view = $this->getView();

$this->app->jbassets->initJBFavorite();

if (count($vars['objects'])) {

    foreach ($vars['objects'] as $id => $item) {

        $layout = $this->app->jblayout->_getItemLayout($item, 'favorite');

        echo '<div class="jsJBZooFavorite favorite-item-wrapper rborder item-' . $item->id . '">';

        echo '<a class="jbbuttom jsJBZooFavoriteRemove" href="' . $this->app->jbrouter->favoriteRemoveItem($item->id) . '" '
            . ' title="' . JText::_('JBZOO_FAVORITE_REMOVE_ITEM') . '">' . JText::_('JBZOO_FAVORITE_REMOVE') . '</a>';

        echo $view->renderer->render($layout, array(
            'view' => $view,
            'item' => $item
        ));

        echo '</div>';
    }

} else {
    echo JText::_('JBZOO_FAVORITE_EMPTY');
}
