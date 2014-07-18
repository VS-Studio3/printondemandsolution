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


class JBElementXmlHelper extends AppHelper
{

    private $_jbzooExtensions = array(
        'mod_jbzoo_search',
        'mod_jbzoo_props',
    );

    /**
     * @param $element       Element
     * @param $params        array
     * @param $requestParams array
     * @return array
     */
    public function editElements($element, $params, $requestParams)
    {
        return $params;
    }

    /**
     * @param $element       Element
     * @param $params        array
     * @param $requestParams array
     * @return array
     */
    public function assignElements($element, $params, $requestParams)
    {
        $newParams = $params;
        if ($extName = $this->_getExtensionName($requestParams['path'])) {

            $newParams = array($params[0]);

            if ($addPath = $this->app->path->path('jbxml:' . $extName . '.xml')) {
                $newParams[] = $addPath;
            }

            if ($addPath = $this->app->path->path('jbxml:' . $extName . '/' . $element->getElementType() . '.xml')) {
                $newParams[] = $addPath;
            } else {
                $newParams[] = $this->app->path->path('jbxml:' . $extName . '/_default.xml');
            }

        }

        return $newParams;
    }

    /**
     * Get extension name
     * @param $path
     * @return null|string
     */
    private function _getExtensionName($path)
    {
        $path  = urldecode($path);
        $parts = explode('/', $path);

        foreach ($this->_jbzooExtensions as $extension) {
            if (in_array($extension, $parts)) {
                return $extension;
            }
        }

        return null;
    }
}