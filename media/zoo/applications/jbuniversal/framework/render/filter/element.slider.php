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


class JBFilterElementSlider extends JBFilterElement
{

    /**
     * Render HTML
     * @return string
     */
    public function html()
    {

        $value = (isset($this->_value['range'])) ? $this->_value['range'] : null;

        $params = array(
            'min'  => $this->_params->get('jbzoo_filter_slider_min', 0),
            'max'  => $this->_params->get('jbzoo_filter_slider_max', 10000),
            'step' => $this->_params->get('jbzoo_filter_slider_step', 100),
        );

        return $this->app->jbhtml->slider(
            $params,
            $value,
            $this->_getName('range'),
            $this->_getId(null, true)
        );

    }

}
