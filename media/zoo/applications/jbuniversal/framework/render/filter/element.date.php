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


class JBFilterElementDate extends JBFilterElement
{
    /**
     * Render HTML
     * @return string
     */
    function html()
    {
        return $this->app->jbhtml->calendar(
            $this->_getName(),
            $this->_value,
            $this->_attrs,
            $this->_getId(null, true),
            $this->_getPickerParams()
        );
    }

    /**
     * Get date format from config
     * @return string
     */
    protected function _getTimeformat()
    {
        return $this->_params->get('jbzoo_date_timeformat', 'hh:mm:ss');
    }

    /**
     * Get time format from config
     * @return string
     */
    protected function _getDateformat()
    {
        return $this->_params->get('jbzoo_date_dateformat', 'yy-mm-dd');
    }

    /**
     * Get params fo JS widget in JSON format
     * @return array
     */
    protected function _getPickerParams()
    {
        $result = array();

        if ($this->_getDateformat()) {
            $result['dateFormat'] = $this->_getDateformat();
        } else {
            $result['dateFormat'] = false;
        }

        if ($this->_getTimeformat()) {
            $result['timeFormat'] = $this->_getTimeformat();
        } else {
            $result['timeFormat'] = '';
            $result['showSecond'] = false;
            $result['showMinute'] = false;
            $result['showHour']   = false;
        }

        return $result;
    }

    /**
     * Get element attrs
     * @param array $attrs
     * @return mixed
     */
    protected function _getAttrs(array $attrs)
    {
        $attrs['class'][] = 'element-datepicker';
        return $attrs;
    }
}
