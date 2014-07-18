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


class JBFilterRenderHelper extends AppHelper
{

    /**
     * @param $elementID
     * @param $type
     * @param $application
     * @return string
     */
    function getElementType($elementID, $type, $application)
    {
        $zooElement  = $this->getElement($elementID, $type, $application);
        $elementType = strtolower(get_class($zooElement));
        return $elementType;
    }

    /**
     * Mapping
     * @param $elementType
     * @return string
     */
    function map($elementType)
    {
        $elementType = str_replace('element', '', $elementType);
        switch ($elementType) {
        case 'text':
            $renderMethod = 'text';
            break;

        case 'radio':
        case 'select':
            $renderMethod = 'select';
            break;

        case 'checkbox':
            $renderMethod = 'checkbox';
            break;

        default:
            $renderMethod = 'text';
            break;
        }

        return $renderMethod;
    }


    /**
     * Build attributes
     * @param $params
     * @return string
     */
    function _buildAttrs($params)
    {
        $attrs = '';
        foreach ($params as $key => $param) {
            $attrs .= ' ' . $key . '="' . $param . '" ';
        }
        return $attrs;
    }

}