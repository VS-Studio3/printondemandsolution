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


class JBFilterElementRatingRanges extends JBFilterElementRating
{

    /**
     * Render HTML
     * @return string|null
     */
    function html()
    {
        $values = $this->_getValues();

        return $this->app->jbhtml->select(
            $this->_createOptionsList($values),
            $this->_getName(),
            $this->_attrs,
            $this->_value,
            $this->_getId()
        );
    }

    /**
     * Get values
     * @param null $type
     * @return array
     */
    protected function _getValues($type = null)
    {
        $start = (int)$this->_config->get('stars', 5);

        $values = array();

        for ($i = 0; $i <= $start; $i++) {

            if ($i + 1 <= $start) {

                $values[] = array(
                    'value' => $i . '/' . ($i + 1),
                    'text'  => JText::_('JBZOO_FROM') . ' ' . $i . ' ' . JText::_('JBZOO_TO') . ' ' . ($i + 1),
                    'count' => null,
                );

            }
        }

        return $values;
    }

}
