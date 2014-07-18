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


class FilterRenderer extends AppRenderer {

    protected $_type = null;
    protected $_template = null;
    protected $_application = null;
    protected $_config_file = 'positions.config';
    protected $_xml_file = 'positions.xml';

    /**
     * Render element
     * @param       $layout
     * @param array $args
     * @return null|string
     */
    public function render($layout, $args = array())
    {
        $this->_template    = $args['layout'];
        $this->_application = $args['application'];
        $this->_type        = $args['type'];

        $this->app->jbfilter->set($this->_type, $this->_application);
        $result = parent::render($layout, $args);

        return $result;
    }

    /**
     * Check position
     * @param string $position
     * @return bool
     */
    public function checkPosition($position)
    {
        foreach ($this->_getConfigPosition($position) as $data) {
            if (isset($data['element'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render position
     * @param string $position
     * @param array  $args
     * @return string
     */
    public function renderPosition($position, $args = array())
    {
        // init vars
        $output = array();
        $i      = 0;

        $this->app->jbdebug->mark('filter::position-' . $position . '::start');

        // TODO check file exists
        $style          = (isset($args['style']) && $args['style']) ? $args['style'] : 'filter.block';
        $elementsConfig = $this->_getConfigPosition($position);

        foreach ($elementsConfig as $data) {

            $element = $this->app->jbfilter->getElement($data['element']);

            if ($element && $element->canAccess()) {

                $i++;

                $params = array_merge(
                    array(
                        'first' => ($i == 1),
                        'last'  => ($i == count($elementsConfig) - 1)
                    ),
                    $data,
                    $args
                );

                $attrs = array(
                    'id'    => 'filterEl_' . $element->identifier,
                    'class' => array(
                        'element-' . strtolower($element->getElementType()),
                        'element-tmpl-' . $params['jbzoo_filter_render']
                    )
                );

                $value       = $this->_getRequest($element->identifier);
                $elementHTML = $this->app->jbfilter->elementRender($element->identifier, $value, $params, $attrs);

                if ($style) {
                    $output[$i] = parent::render($style, array(
                            'element'     => $element,
                            'params'      => $params,
                            'attrs'       => $attrs,
                            'value'       => $value,
                            'config'      => $element->getConfig(),
                            'elementHTML' => $elementHTML
                        )
                    );

                } else {
                    $output[$i] = $elementHTML;

                }
            }
        }

        $this->app->jbdebug->mark('filter::position-' . $position . '::end');

        return implode("\n", $output);
    }

    /**
     * Get element request
     * @param $identifier
     * @return null|array|string
     */
    private function _getRequest($identifier)
    {
        $value = $this->app->jbrequest->get($identifier);

        if (!$value) {

            $elements = $this->app->jbrequest->get('e');

            if (is_array($elements)) {
                return (isset($elements[$identifier]) ? $elements[$identifier] : null);
            }

        }

        return $value;
    }

    /**
     * Get render config
     * @param $dir
     * @return array
     */
    public function getConfig($dir)
    {
        // config file
        if (empty($this->_config)) {
            if ($file = $this->_path->path('default:' . $dir . '/' . $this->_config_file)) {
                $content = JFile::read($file);
            } else {
                $content = null;
            }

            $this->_config = $this->app->parameter->create($content);
        }

        return $this->_config;
    }

    /**
     * Check path
     * @param $dir
     * @return bool
     */
    public function pathExists($dir)
    {
        return (bool)$this->_getPath($dir);
    }

    /**
     * Get config position
     * @param string $position
     * @return array
     */
    protected function _getConfigPosition($position)
    {
        $configName = $this->_application->getGroup() . '.' . $this->_type . '.' . $this->_template;
        $config     = $this->getConfig('item')->get($configName);
        return $config && isset($config[$position]) ? $config[$position] : array();
    }

}