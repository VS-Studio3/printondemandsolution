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
$this->app->jbassets->payment();

echo '<h2>' . JText::_('JBZOO_ORDER_NAME') . ' #' . $view->order->id . '</h2>';

echo $view->orderDetails->render(array(
    'payment-button' => false,
    'payment-info'   => false,
    'template'       => 'table',
));

if ((int)$view->appParams->get('global.jbzoo_cart_config.robox-enabled', 0)) {
    echo '<div class="width50">';
    echo $this->app->jblayout->render('payment_robox', $view->payments['robox']);
    echo '</div>';
}

if ((int)$view->appParams->get('global.jbzoo_cart_config.ikassa-enabled', 0)) {
    echo '<div class="width50">';
    echo $this->app->jblayout->render('payment_ikassa', $view->payments['ikassa']);
    echo '</div>';
}
