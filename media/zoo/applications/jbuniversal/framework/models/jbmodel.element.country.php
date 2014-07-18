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


class JBModelElementCountry extends JBModelElement
{

    /**
     * Prepare value
     * @param $values
     * @param $exact
     * @return mixed
     */
    protected function _prepareValue($values, $exact)
    {
        if ($exact) {
            return $values;

        } else {
            $options = $this->_getCountries();

            $result = array();
            if (!is_array($values)) {
                $values = array($values);
            }

            foreach ($values as $value) {
                if (isset($options[$value])) {
                    $result[] = $options[$value];
                }
            }

            return $result;
        }
    }

    /**
     * Get values
     * @return array
     */
    private function _getCountries()
    {
        $selectable_countries = $this->_config->get('selectable_country', array());
        $countries            = $this->app->country->getIsoToNameMapping();
        $keys                 = array_flip($selectable_countries);
        $countries            = array_intersect_key($countries, $keys);

        $result = array();
        foreach ($countries as $country) {
            $translated          = JText::_($country);
            $result[$translated] = $country;
        }

        return $result;
    }
}
