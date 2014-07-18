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

$this->app->jbdebug->mark('layout::compare::start');

// get render
$view   = $this->getView();
$render = $view->renderer;

// render table cells items
$renderedItems = $render->renderFields($view->itemType, $view->appId, $vars['objects']);
$elementList   = $render->getElementList($renderedItems);

$this->app->jbdebug->mark('layout::compare::renerItems');

// render top compare links
$horizontalUrl = $this->app->jbrouter->compare($view->itemId, 'h', $view->itemType, $view->appId);
$verticalUrl   = $this->app->jbrouter->compare($view->itemId, 'v', $view->itemType, $view->appId);
$clearUrl      = $this->app->jbrouter->compareClear($view->itemId, $view->itemType, $view->appId);

// add links
echo '<div class="jbzoo-compare-links">';
if ($view->layoutType == 'h') {
    echo '<a href="' . $verticalUrl . '">' . JTEXT::_('JBZOO_COMPARE_VERTICAL') . '</a> &mdash; '
        . '<span>' . JTEXT::_('JBZOO_COMPARE_HORIZONTAL') . '</span>';
} else {
    echo '<span>' . JTEXT::_('JBZOO_COMPARE_VERTICAL') . '</span> &mdash; '
        . '<a href="' . $horizontalUrl . '">' . JTEXT::_('JBZOO_COMPARE_HORIZONTAL') . '</a>';
}

echo '<a href="' . $clearUrl . '" title="' . JText::_('JBZOO_COMPARE_REMOVEALL') . '" class="compare-clear">' . JText::_('JBZOO_COMPARE_REMOVEALL') . '</a>';
echo '</div>';

$this->app->jbdebug->mark('layout::compare::renerLins');

// render compare table html
if ($view->layoutType == 'v') {

    echo '<table class="jbcompare-table vertical">';

    // head
    echo '<thead><tr><td class="element-names">&nbsp;</td>';
    foreach ($renderedItems as $itemId => $itemHtml) {
        $link  = $this->app->route->item($vars['objects'][$itemId]);
        $title = $itemHtml['itemname'];
        echo '<th><a href="' . $link . '" title="' . $title . '">' . $title . '</a></th>' . "\n";
    }
    echo '</tr></thead>';

    // body
    echo '<tbody>';
    foreach ($elementList as $elementId) {

        if ($elementId != 'itemname') {

            $label = $render->renderElementLabel($elementId, $view->itemType, $view->appId);

            echo '<tr><th>' . $label . '</th>';
            foreach ($renderedItems as $itemId => $itemElements) {
                echo '<td>' . $itemElements[$elementId] . '</td>' . "\n";
            }
            echo '</tr>';
        }

    }

    echo '</tbody></table>';

} else if ($view->layoutType == 'h') {

    echo '<table class="jbcompare-table horizontal">';

    echo '<thead><tr><td class="item-names">&nbsp;</td>';
    foreach ($elementList as $elementId) {
        if ($elementId != 'itemname') {
            echo '<th>' . $render->renderElementLabel($elementId, $view->itemType, $view->appId) . '</th>' . "\n";
        }
    }
    echo '</tr></thead>';

    echo '<tbody>';
    foreach ($renderedItems as $itemId => $itemElements) {

        echo '<tr>';
        foreach ($itemElements as $elementId => $elementHtml) {

            if ($elementId == 'itemname') {
                $link  = $this->app->route->item($vars['objects'][$itemId]);
                $title = $elementHtml;
                echo '<th><a href="' . $link . '" title="' . $title . '">' . $title . '</a></th>' . "\n";

            } else {
                echo '<td>' . $elementHtml . '</td>' . "\n";
            }
        }
        echo '</tr>';
    }

    echo '</tbody></table>';

} else {
    throw new AppException($view->layoutType . ' - Unknow layout!');
}

$this->app->jbdebug->mark('layout::compare::finish');
