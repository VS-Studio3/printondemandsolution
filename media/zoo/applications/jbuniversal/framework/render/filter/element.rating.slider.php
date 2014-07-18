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


class JBFilterElementRatingSlider extends JBFilterElementRating
{

    /**
     * Render HTML
     * @return string
     */
    public function html()
    {
        $params = array(
            'min'  => 0,
            'max'  => $this->_config->get('stars'),
            'step' => 1,
        );

        return $this->app->jbhtml->slider(
            $params,
            $this->_value,
            $this->_getName(),
            $this->_getId()
        );

    }
}
