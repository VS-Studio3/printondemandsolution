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


class JBModelElementJBSelectCascade extends JBModelElement
{

    /**
     * Prepare value
     * @param string|array $value
     * @param boolean      $exact
     * @return mixed
     */
    protected function _prepareValue($value, $exact = false)
    {
        if (!is_array($value)) {
            $value = array($value);
        }

        $values = array_reverse($value);

        foreach ($values as $valueRow) {
            if (!empty($valueRow)) {
                return $valueRow;
            }
        }

        return $value;
    }

}