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


class JBFilterElementCountryCheckbox extends JBFilterElementCountry
{
    /**
     * Render HTML
     * @return string
     */
    public function html()
    {
        $this->_isMultiple = true;

        $values = $this->_getValues();

        return $this->app->jbhtml->checkbox(
            $this->_createOptionsList($values),
            $this->_getName(),
            $this->_attrs,
            $this->_value,
            $this->_getId()
        );
    }

}
