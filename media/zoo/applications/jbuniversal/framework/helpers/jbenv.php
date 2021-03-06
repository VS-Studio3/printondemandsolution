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


class JBEnvHelper extends AppHelper
{

    /**
     * Is current application is front-end
     * @return bool
     */
    public function isSite()
    {
        return JFactory::getApplication()->isSite();
    }


    /**
     * Get current template name
     * @return string
     */
    public function getTemplateName()
    {
        $templateName = 'catalog';
        $currentApp   = $this->app->zoo->getApplication();
        if ($currentApp) {
            $templateName = $currentApp->getTemplate()->name;
        }

        return $templateName;
    }

    /**
     * Get full current URL
     */
    public function getCurrentUrl()
    {
        return JUri::getInstance()->toString();
    }

    /**
     * Check, is widgetkit enabled
     */
    public function isWidgetkit($isFree = true)
    {
        $isFreeResult = JFile::exists(JPATH_ADMINISTRATOR . '/components/com_widgetkit/classes/widgetkit.php')
            && JComponentHelper::getComponent('com_widgetkit', true)->enabled
            && JFile::exists(JPATH_ADMINISTRATOR . '/components/com_zoo/config.php')
            && JComponentHelper::getComponent('com_zoo', true)->enabled;

        if ($isFreeResult && $isFree) {
            return true;
        }

        if ($isFreeResult && !$isFree && $this->app->path->path('media:widgetkit/widgets/accordion')) {
            return true;
        }

        return false;
    }

}
