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


class JBFilterElementText extends JBFilterElement
{
    /**
     * Get main attrs
     * @param array $attrs
     * @return array
     */
    protected function _getAttrs(array $attrs)
    {
        $attrs = parent::_getAttrs($attrs);

        $attrs['maxlength'] = '255';
        $attrs['size']      = '60';

        if ((int)$this->_params->get('jbzoo_filter_autocomplete', 0)) {
            $attrs['class'][] = 'jsAutocomplete';

            $default = JText::_('JBZOO_FILTER_PLACEHOLDER_DEFAULT');

            $placeholderText = $this->_params->get('jbzoo_filter_placeholder', $default);

            if ($placeholderText) {
                $attrs['placeholder'] = $placeholderText;
            } else {
                $attrs['placeholder'] = $default;
            }

        }

        return $attrs;
    }

}
