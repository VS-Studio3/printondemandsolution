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

require_once App::getInstance('zoo')->path->path('renderer:item.php');

class CompareRenderer extends ItemRenderer
{

    const COMPARE_POSITION = 'fields';

    /**
     * Render constructor
     * @param App  $app
     * @param null $path
     */
    public function __construct($app, $path = null)
    {
        parent::__construct($app, $path);
        $this->_layout = 'compare';
    }

    /**
     * Get position data
     * @param $position
     * @param $type
     * @param $appId
     * @param $forceLayout
     * @return array
     */
    public function getPositionData($position, $type, $appId, $forceLayout = null)
    {
        if (!$forceLayout) {
            $forceLayout = $this->_layout;
        }

        $application = $this->app->table->application->get($appId);
        $path        = $application->getGroup() . '.' . $type . '.' . $forceLayout;
        $config      = $this->getConfig('item')->get($path);

        if ($config) {
            return isset($config[$position]) ? $config[$position] : array();
        }

        return array();
    }

    /**
     * Render item element
     * @param string $elementId
     * @param Item   $item
     * @param array  $params
     * @return string
     */
    public function renderItemElement($elementId, Item $item, $params = array())
    {
        $element = $item->getElement($elementId);

        if ($element && $item) {
            return parent::render("element.default", array(
                'params'  => $params,
                'element' => $element,
                'item'    => $item,
            ));
        }

        return null;
    }

    /**
     * Render items
     * @param string $type
     * @param int    $appId
     * @param array  $items
     * @return array
     */
    public function renderFields($type, $appId, array $items)
    {
        $elements = $this->getPositionData(self::COMPARE_POSITION, $type, (int)$appId);

        $renderedItems = array();

        foreach ($items as $item) {

            $renderedItems[$item->id] = array('itemname' => $item->name);

            foreach ($elements as $element) {
                $renderedItems[$item->id][$element['element']] = $this->renderItemElement($element['element'], $item, $element);
            }

        }

        return $renderedItems;
    }

    /**
     * Get clear element list
     * @param array $renderedItems
     * @return array
     */
    public function getElementList(array $renderedItems)
    {
        reset($renderedItems);
        $sampleItem  = current($renderedItems);
        $elementList = array_keys($sampleItem);

        return $elementList;
    }

    /**
     * Render label
     * @param $elementId
     * @param $itemType
     * @param $appId
     * @return mixed
     */
    public function renderElementLabel($elementId, $itemType, $appId)
    {
        $elements = $this->getPositionData(self::COMPARE_POSITION, $itemType, (int)$appId, 'compare');

        $itemType           = $this->app->jbentity->getType($itemType, $appId);
        $typeElementsConfig = $itemType->config->get('elements');

        $resultLabel = null;
        foreach ($elements as $element) {
            if ($element['element'] == $elementId) {
                if ($element['altlabel']) {
                    $resultLabel = $element['altlabel'];
                    break;
                }
            }
        }

        if (!$resultLabel && isset($typeElementsConfig[$elementId]['name'])) {
            $resultLabel = $typeElementsConfig[$elementId]['name'];
        }

        return $resultLabel;
    }

}
