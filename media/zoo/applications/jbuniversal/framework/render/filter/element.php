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


class JBFilterElement {
    /**
     * @var App
     */
    public $app = null;

    /**
     * @var string
     */
    protected $_identifier = '';

    /**
     * @var array|string
     */
    protected $_value = null;

    /**
     * @var ParameterData
     */
    protected $_params = null;

    /**
     * @var array
     */
    protected $_attrs = array();

    /**
     * @var JSONData
     */
    protected $_config = null;

    /**
     * @var boolean
     */
    protected $_isOrigTmpl = true;

    /**
     * @var bool
     */
    protected $_isMultiple = false;

    /**
     * @var bool
     */
    protected $_isCountShow = true;

    /**
     * Constructor
     * @param $identifier string
     * @param $value      string|array
     * @param $params     array
     * @param $attrs      array
     */
    function __construct($identifier, $value, array $params, array $attrs)
    {
        $this->app     = App::getInstance('zoo');
        $this->_params = $this->app->parameter->create($params);

        $this->_identifier = $identifier;

        $this->_value = $this->_getElementValue($value);

        $this->_isOrigTmpl  = (int)$this->_params->get('jbzoo_original_type', 1);
        $this->_isMultiple  = (int)$this->_params->get('jbzoo_filter_multiple', 0);
        $this->_isCountShow = (int)$this->_params->get('jbzoo_filter_count', 1);

        $this->_attrs  = $this->_getAttrs($attrs);
        $this->_config = $this->app->jbfilter->getElement($this->_identifier)->getConfig();
    }

    /**
     * Get elemtn value
     * @param $value
     * @return mixed
     */
    protected function _getElementValue($value)
    {
        if (!$value && $value = $this->_params->get('jbzoo_filter_default', null)) {

            $value = trim($value);

            if (strpos($value, '{') !== false && strpos($value, '}') !== false) {
                $value = json_decode($value, true);
            }

        }

        return $value;
    }

    /**
     * Get available values
     * @param null $type
     * @return array
     */
    protected function _getValues($type = null)
    {
        $result = null;

        if ($type == 'db') {
            $result = $this->_getDbValues();

        } elseif ($type == 'bool') {
            $result = $this->_getBoolValues();

        } elseif ($type == 'config') {
            $result = $this->_getConfigValues();
        }

        if (empty($result)) {
            $result = array();
        }

        return $result;
    }

    /**
     * Get data from db index table by element identifier
     * @return array
     */
    protected function _getDbValues()
    {
        return JBModelValues::model()->getPropsValues($this->_identifier);
    }

    /**
     * Get boolean values
     * @return array
     */
    protected function _getBoolValues()
    {
        $result = array(
            array(
                'text'  => JText::_('JBZOO_YES'),
                'value' => 1,
                'count' => null
            ),
            array(
                'text'  => JText::_('JBZOO_NO'),
                'value' => 0,
                'count' => null
            )
        );

        return $result;
    }

    /**
     * Get config from options
     * @return mixed|null
     */
    protected function _getConfigValues()
    {
        $options = $this->_config->get('spin', array());

        foreach ($options as $key=> $option) {
            $options[$key]['count'] = null;
        }

        return $options;
    }

    /**
     * Get html attributs
     * @param $attrs
     * @return array
     */
    protected function _getAttrs(array $attrs)
    {
        if ($this->_isMultiple) {
            $attrs['multiple'] = 'multiple';

            if (!isset($attrs['size'])) {
                $attrs['size'] = '5';
            }
        }

        return $attrs;
    }

    /**
     * @param array $values
     * @param bool  $showAll
     * @return array
     */
    protected function _createOptionsList($values, $showAll = true)
    {
        $options = array();

        if (!$this->_isMultiple && $showAll) {
            $options[] = $this->app->html->_('select.option', '', ' - ' . JText::_('JBZOO_ALL') . ' - ');
        }

        foreach ($values as $value) {
            $name = $value['text'];

            if (!empty($value['count']) && $this->_isCountShow) {
                $name = $name . ' (' . $value['count'] . ')';
            }

            $options[] = $this->app->html->_('select.option', $value['value'], $name);
        }

        return $options;
    }

    /**
     * Get element ID attribute
     * @param string $postFix
     * @param bool   $addUniq
     * @return string
     */
    protected function _getId($postFix = null, $addUniq = false)
    {
        static $uniqNumber;

        if (!isset($uniqNumber)) {
            $uniqNumber = 0;
        }

        $id = $this->_attrs['id'];

        if ($postFix !== null) {
            $id .= '-' . $postFix;
        }

        $uniqNumber++;

        if ($addUniq) {
            $id = $id . '-' . $uniqNumber;
        }

        return $id;
    }

    /**
     * Get element name
     * @param string $postFix
     * @return string
     */
    protected function _getName($postFix = null)
    {
        $name = 'e[' . $this->_identifier . ']';

        if ($postFix !== null) {
            $name .= '[' . $postFix . ']';
        }

        return $name;
    }

    /**
     * Render HTML code for element
     * @return string|null
     */
    public function html()
    {
        return $this->app->jbhtml->text(
            $this->_getName(),
            $this->_value,
            $this->_attrs,
            $this->_getId()
        );
    }
}
