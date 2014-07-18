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


class JBFilterPropsHelper extends AppHelper
{
    /**
     * Element render
     * @param       $identifier
     * @param bool  $value
     * @param array $params
     * @return bool
     */
    public function elementRender($identifier, $value = null, $params = array())
    {
        //get configs
        $showCount = (int)$params['moduleParams']->get('count', 1);
        $isDepend  = (int)$params['moduleParams']->get('depend', 1);

        $elements    = $isDepend ? $this->app->jbrequest->getElements() : array();
        $propsValues = JBModelValues::model()->getPropsValues($identifier, null, null, $elements);

        if (!empty($propsValues)) {

            $html = array();
            foreach ($propsValues as $propsValue) {

                $class = '';
                if ($this->_isActive($identifier, $propsValue['value'])) {

                    $link = $this->app->jbrouter->filter(
                        $identifier, $propsValue['value'], $params['moduleParams'], 2
                    );

                    $class = ' class="active"';

                } else {
                    $link = $this->app->jbrouter->filter(
                        $identifier, $propsValue['value'], $params['moduleParams'], ($isDepend ? 1 : 0)
                    );
                }

                // render html list item
                $html[] = '<li' . $class . '><a href="' . $link . '" title="' . $propsValue['value'] . '"><span>'
                    . $propsValue['value'] . ' '
                    . (($showCount) ? '<span class="element-count">(' . $propsValue['count'] . ')</span>' : '')
                    . '</span></a>'
                    . ($class ? '<a href="' . $link . '" class="cancel">&nbsp;</a>' : '')
                    . '</li>';

            }

            return '<ul class="jbzoo-props-list">' . implode("\n", $html) . '</ul>';
        }

        return '';
    }

    /**
     * Check is active
     * @param string $identifier
     * @param string $value
     * @return bool
     */
    protected function _isActive($identifier, $value)
    {

        $elements = $this->app->jbrequest->getElements();

        if (isset($elements[$identifier])) {

            if (is_string($elements[$identifier])) {
                return JString::strtolower($elements[$identifier]) == JString::strtolower(JString::trim($value));

            } else {
                return in_array($value, $elements[$identifier]);

            }
        }

        return false;
    }

}
