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

jimport('joomla.plugin.plugin');
jimport('joomla.filesystem.file');

class plgSystemJBZoo extends JPlugin
{

    /**
     * Event onAfterInitialise Joomla
     * @return mixed
     */
    public function onAfterInitialise()
    {

        $componentEnabled = JComponentHelper::getComponent('com_zoo', true)->enabled;
        if (!$componentEnabled) {
            return;
        }

        $mainConfig = JPATH_ADMINISTRATOR . '/components/com_zoo/config.php';
        if (!JFile::exists($mainConfig)) {
            return;
        }

        require_once ($mainConfig);
        if (!class_exists('App')) {
            return;
        }

        $zoo = App::getInstance('zoo');
        if ($id = $zoo->request->getInt('changeapp')) {
            $zoo->system->application->setUserState('com_zooapplication', $id);
        }

        $this->initEvents();

        $jbzooBootstrap = JPATH_ROOT . '/media/zoo/applications/jbuniversal/framework/jbzoo.php';
        if (JFile::exists($jbzooBootstrap)) {
            require_once ($jbzooBootstrap);
            JBZoo::init();
        }
    }

    /**
     * Init system events
     */
    public function initEvents()
    {
        $zoo = App::getInstance('zoo');
        $zoo->event->register('plgSystemJBZoo');
        $zoo->event->dispatcher->connect('application:installed', array('plgSystemJBZoo', 'applicationInstall'));
    }

    /**
     * Application install
     * @param $event
     */
    public function applicationInstall($event)
    {
        $app = $event->getSubject();

        if ($app->application_group == 'jbuniversal') {

            $version = (string)$app->getMetaXML()->version;

            $updateScript = dirname(__FILE__) . '/update/' . $version . '.php';

            if (JFile::exists($updateScript)) {
                require_once($updateScript);
                JBZooUpdater::init($app);
            }

        }

    }
}
