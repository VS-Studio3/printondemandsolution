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

class JBEventElement extends JBEvent
{

    /**
     * Attach new element options for jbzoo extensions
     * @static
     * @param AppEvent $event
     */
    public static function configParams(AppEvent $event)
    {
        $app = self::app();

        if ($app->jbrequest->is('group', JBZOO_APP_GROUP)) {

            // extract event
            $element = $event->getSubject();
            $params  = $event->getReturnValue();

            $sxmlPaths = array();

            // get extranal vars
            $requestParams = array(
                'path'   => $app->jbrequest->get('path'),
                'type'   => $app->jbrequest->get('type'),
                'layout' => $app->jbrequest->get('layout'),
                'cid'    => $app->jbrequest->get('cid'),
            );

            // add new xml params
            if ($app->jbrequest->is('task', 'editelements')) {
                $params = $app->jbelementxml->editElements($element, $params, $requestParams);

            } elseif ($app->jbrequest->is('task', 'assignelements')) {
                $params = $app->jbelementxml->assignElements($element, $params, $requestParams);
            }
            
            // set params to element
            $event->setReturnValue($params);
        }

    }

    public static function download($event)
    {
    }

    public static function configForm($event)
    {
    }

    public static function configXML($event)
    {
    }

    public static function afterDisplay($event)
    {
    }

    public static function beforeDisplay($event)
    {
    }

    public static function afterSubmissionDisplay($event)
    {
    }

    public static function beforeSubmissionDisplay($event)
    {
    }

    public static function afterEdit($event)
    {
    }
}