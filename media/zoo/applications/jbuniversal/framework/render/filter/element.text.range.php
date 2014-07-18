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

require_once dirname(__FILE__) . '/element.text.php';

class JBFilterElementTextRange extends JBFilterElementText
{
    /**
     * Render HTML
     * @return string
     */
    function html()
    {
        $html = array();

        $values = (isset($this->_value['range'])) ? $this->_value['range'] : array('', '');

        $html[] = '<label for="' . $this->_getId('1') . '">' . JText::_('JBZOO_FROM') . '</label>';
        $html[] = $this->app->jbhtml->text(
            $this->_getName('0'),
            $values[0],
            $this->_attrs,
            $this->_getId('1')
        );

        $html[] = '<br />';

        $html[] = '<label for="' . $this->_getId('2') . '">' . JText::_('JBZOO_TO') . '</label>';
        $html[] = $this->app->jbhtml->text(
            $this->_getName('1'),
            $values[1],
            $this->_attrs,
            $this->_getId('2')
        );

        return implode("\n\r", $html);
    }

    /**
     * Get name
     * @param $postFix
     * @return string
     */
    protected function _getName($postFix = null)
    {
        return parent::_getName('range') . '[' . $postFix . ']';
    }
}
