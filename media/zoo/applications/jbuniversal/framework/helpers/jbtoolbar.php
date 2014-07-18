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


class JBToolbarHelper extends AppHelper
{

    /**
     * Show toolbar buttons
     */
    public function toolbar()
    {
        if ($this->app->jbrequest->is('controller', 'manager') &&
            $this->app->jbrequest->is('task', 'types') &&
            $this->app->jbrequest->is('group', 'jbuniversal')
        ) {
            $this->_link('stats', 'JBZOO_BUTTON_CHECK_DB', array(
                'controller' => 'jbtools',
                'task'       => 'checkdb',
            ));

            $this->_popup('licence', 'JBZOO_BUTTON_LICENCE', array(
                'controller' => 'jbtools',
                'task'       => 'licence',
            ), 500, 270);


            if (!defined('JBZOO_CONFIG_SHOWUPDATE') || JBZOO_CONFIG_SHOWUPDATE) {
                $this->_link('stats', 'JBZOO_BUTTON_TOGGLEUPDATE_NO', array(
                    'controller' => 'jbtools',
                    'task'       => 'toggleupdate',
                ));
            } else {
                $this->_link('stats', 'JBZOO_BUTTON_TOGGLEUPDATE_YES', array(
                    'controller' => 'jbtools',
                    'task'       => 'toggleupdate',
                ));
            }

        }
    }

    /**
     * Show button for popup window
     * @param string $icon
     * @param string $name
     * @param array  $urlParams
     * @param int    $width
     * @param int    $height
     */
    protected function _popup($icon, $name, array $urlParams, $width = 600, $height = 450)
    {
        $urlParams = array_merge(array(
            'option' => 'com_zoo',
            'tmpl'   => 'component'
        ), $urlParams);

        $link = JRoute::_(JURI::root() . 'administrator/index.php?' . http_build_query($urlParams), true, -1);

        JToolBar::getInstance('toolbar')->appendButton('Popup', $icon, $name, $link, $width, $height);
    }

    /**
     * Show link-button
     * @param string $icon
     * @param string $name
     * @param array  $urlParams
     */
    protected function _link($icon, $name, $urlParams)
    {
        $urlParams = array_merge(array(
            'option' => 'com_zoo',
            'tmpl'   => 'component'
        ), $urlParams);

        $link = JRoute::_(JURI::root() . 'administrator/index.php?' . http_build_query($urlParams), true, -1);

        JToolBar::getInstance('toolbar')->appendButton('Link', $icon, $name, $link);
    }
}