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

require_once dirname(__FILE__) . '/element.date.php';

class JBFilterElementDateRange extends JBFilterElementDate
{
    /**
     * Render HTML
     * @return string
     */
    function html()
    {
        $html = array();

        if (is_string($this->_value)) {
            $value = array($this->_value, $this->_value);
        } else {
            $value = (isset($this->_value['range-date'])) ? $this->_value['range-date'] : array('', '');
        }

        $html[] = '<label for="' . $this->_getId('1') . '">' . JText::_('JBZOO_FROM') . '</label>';
        $html[] = $this->app->jbhtml->calendar(
            $this->_getName('0'),
            $value[0],
            $this->_attrs,
            $this->_getId('1', true),
            $this->_getPickerParams()
        );

        $html[] = '<br />';

        $html[] = '<label for="' . $this->_getId('2') . '">' . JText::_('JBZOO_TO') . '</label>';
        $html[] = $this->app->jbhtml->calendar(
            $this->_getName('1'),
            $value[1],
            $this->_attrs,
            $this->_getId('2', true),
            $this->_getPickerParams()
        );

        return implode("\n\r", $html);
    }

    /**
     * Get name
     * @param null $postFix
     * @return string
     */
    protected function _getName($postFix = null)
    {
        return parent::_getName('range-date') . '[' . $postFix . ']';
    }
}
