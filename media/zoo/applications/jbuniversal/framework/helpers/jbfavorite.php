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


class JBFavoriteHelper extends AppHelper
{

    protected $_namespace = 'jbzoo';
    protected $_namespaceHelper = 'jbfavorite';

    /**
     * Check is current user is auth
     * @return int|null
     */
    public function isAuth()
    {
        return (bool)JFactory::getUser()->id;
    }

    /**
     * Get all items from session
     * @return mixed
     */
    public function getAllItems()
    {
        if ($this->isAuth()) {
            $items = JBModelFavorite::model()->getAllItems();
        } else {
            $session = $this->_getSession();
            $items   = $session->get('items', array());
        }

        return $items;
    }

    /**
     * Toggle item state
     * @param Item $item
     * @return bool
     */
    public function toggleState(Item $item)
    {
        if ($this->isAuth()) {
            return JBModelFavorite::model()->toggleItem($item);

        } else {

            if ($this->isExists($item)) {
                $this->_removeItem($item);

                return false;
            }

            $this->_addItem($item);

            return true;
        }
    }

    /**
     * Check is item is compared
     * @param Item $item
     * @return bool
     */
    public function isExists(Item $item)
    {
        if ($this->isAuth()) {
            return JBModelFavorite::model()->isExists($item);

        } else {
            $items = $this->getAllItems();

            return isset($items[$item->id]);
        }
    }

    /**
     * Remove all items from session
     */
    public function removeItems()
    {
        if ($this->isAuth()) {
            JBModelFavorite::model()->removeItems();

        } else {
            $this->_setSession('items', array());
        }

    }

    /**
     * Add item to compare
     * @param Item $item
     */
    protected function _addItem(Item $item)
    {
        $items = $this->getAllItems();

        if (!isset($items[$item->type])) {
            $items[$item->id] = array(
                'id'      => null,
                'item_id' => $item->id,
                'user_id' => JFactory::getUser()->id,
                'date'    => $this->app->jbdate->toMysql(time()),
            );
        }

        $this->_setSession('items', $items);
    }

    /**
     * Remove item from compare
     * @param Item $item
     */
    protected function _removeItem(Item $item)
    {
        $items = $this->getAllItems();

        if ($this->isExists($item)) {
            unset($items[$item->id]);
        }

        $this->_setSession('items', $items);
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