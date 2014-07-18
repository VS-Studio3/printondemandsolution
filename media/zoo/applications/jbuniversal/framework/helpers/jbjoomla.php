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


class JBJoomlaHelper extends AppHelper
{

    /**
     * Render modules by position name
     * @param string $position
     * @param array  $options
     * @return string
     */
    public function renderPosition($position, array $options = array())
    {
        $this->app->jbdebug->mark('jbjoomla::renderPosition (' . $position . ')::start');

        $document     = JFactory::getDocument();
        $renderer     = $document->loadRenderer('modules');
        $positionHtml = $renderer->render($position, $options, null);

        $this->app->jbdebug->mark('jbjoomla::renderPosition (' . $position . ')::finish');

        return $positionHtml;
    }

    /**
     * Render module by id
     * @param int $moduleId
     * @return null|string
     */
    public function renderModuleById($moduleId)
    {
        $this->app->jbdebug->mark('jbjoomla::renderModuleById (' . $moduleId . ')::start');

        $modules = $this->app->module->load();

        if ($moduleId && isset($modules[$moduleId])) {

            if ($modules[$moduleId]->published) {
                $rendered = JModuleHelper::renderModule($modules[$moduleId]);

                $this->app->jbdebug->mark('jbjoomla::renderModuleById (' . $moduleId . ')::finish');

                return $rendered;
            }

        }

        $this->app->jbdebug->mark('jbjoomla::renderModuleById (' . $moduleId . ')::finish');

        return null;
    }

}
