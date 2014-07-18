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

class JBEventBasket extends JBEvent
{

    /**
     * After order saved event
     * @static
     * @param AppEvent $event
     */
    public static function saved($event)
    {
        $app = self::app();

        $params = $event->getParameters();

        $appParams = $params['appParams'];
        $item      = $params['item'];
        $subject   = JText::_('JBZOO_CART_NEW_ORDER_CREATE');

        $adminEmail  = $params['appParams']->get('global.jbzoo_cart_config.admin-email');
        $adminLayout = $appParams->get('global.jbzoo_cart_config.email-admin-layout');
        $app->jbemail->sendByItem($adminEmail, $subject, $item, $adminLayout);

        $userEmail  = JFactory::getUser()->email;
        $userLayout = $appParams->get('global.jbzoo_cart_config.email-user-layout');
        $app->jbemail->sendByItem($userEmail, $subject, $item, $userLayout);
    }

}