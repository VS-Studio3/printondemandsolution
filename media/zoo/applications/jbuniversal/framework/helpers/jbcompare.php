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


class JBCompareHelper extends AppHelper
{

    protected $_namespace = 'jbzoo';
    protected $_namespaceHelper = 'jbcompare';

    /**
     * Get all items from session
     * @return mixed
     */
    public function getAllItems()
    {
        $session = $this->_getSession();
        $items   = $session->get('items', array());
        return $items;
    }

    /**
     * Get items by type
     * @param $itemType
     * @return mixed
     */
    public function getItemsByType($itemType)
    {
        $items = $this->getAllItems();
        return isset($items[$itemType]) ? $items[$itemType] : array();
    }

    /**
     * Add item to compare
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $items = $this->getAllItems();

        if (!isset($items[$item->type])) {
            $items[$item->type] = array();
        }

        $items[$item->type][$item->id] = $item->id;

        $this->_setSession('items', $items);
    }

    /**
     * Remove item from compare
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $items = $this->getAllItems();

        if ($this->isExists($item)) {
            unset($items[$item->type][$item->id]);
        }

        $this->_setSession('items', $items);
    }

    /**
     * Toggle item state
     * @param Item $item
     * @return bool
     */
    public function toggleState(Item $item)
    {
        if ($this->isExists($item)) {
            $this->removeItem($item);
            return false;
        }

        $this->addItem($item);
        return true;
    }

    /**
     * Check is item is compared
     * @param Item $item
     * @return bool
     */
    public function isExists(Item $item)
    {
        $items = $this->getAllItems();

        return isset($items[$item->type][$item->id]);
    }

    /**
     * Remove all items from session
     */
    public function removeItems()
    {
        $this->_setSession('items', array());
    }

    /**
     * Get session
     * @return JSONData
     */
    protected function _getSession()
    {
        $session   = JFactory::getSession();
        $jbcompare = $session->get($this->_namespaceHelper, array(), $this->_namespace);
        $result    = $this->app->data->create($jbcompare);

        return $result;
    }

    /**
     * Set session
     * @param $key
     * @param $value
     */
    protected function _setSession($key, $value)
    {
        $session   = JFactory::getSession();
        $jbcompare = $session->get($this->_namespaceHelper, array(), $this->_namespace);

        $jbcompare[$key] = $value;

        $session->set($this->_namespaceHelper, $jbcompare, $this->_namespace);
    }

}