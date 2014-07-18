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


class JBCartHelper extends AppHelper
{

    protected $_namespace = 'jbzoo';
    protected $_namespaceHelper = 'jbcart';

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
     * Add item to compare
     * @param Item  $item
     * @param array $params
     */
    public function addItem(Item $item, array $params = array())
    {
        $items = $this->getAllItems();

        if (!isset($items[$item->type])) {
            $items[$item->id] = array();
        }

        $items[$item->id] = $params;

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
            unset($items[$item->id]);
        }

        $this->_setSession('items', $items);
    }

    /**
     * Check is item is compared
     * @param Item $item
     * @return bool
     */
    public function isExists(Item $item)
    {
        $items = $this->getAllItems();

        return isset($items[$item->id]);
    }

    /**
     * Change quantity
     * @param Item $item
     * @param int  $value
     */
    public function changeQuantity(Item $item, $value)
    {
        $value = (int)$value;
        $items = $this->getAllItems();

        if ($this->isExists($item)) {
            $items[$item->id]['quantity'] = $value;
        }

        $this->_setSession('items', $items);
    }

    /**
     * Remove all items from session
     */
    public function removeItems()
    {
        $this->_setSession('items', array());
    }

    /**
     * Get array id list
     * @return array
     */
    public function getItemIds()
    {
        return array_keys($this->getAllItems());
    }

    /**
     * Get array id list
     * @param ParameterData $appParams
     * @return array
     */
    public function recount($appParams)
    {
        $itemsPrice = array();
        $count      = 0;
        $total      = 0;

        $items = $this->getAllItems();

        $currencyConvert = $appParams->get('global.jbzoo_cart_config.currency', 'rub');

        foreach ($items as $itemId => $item) {

            $item['price'] = $this->app->jbmoney->convert($item['currency'], $currencyConvert, $item['price']);

            $itemsPrice[$itemId] = $item['price'] * $item['quantity'];

            $count += $item['quantity'];
            $total += $itemsPrice[$itemId];

            $itemsPrice[$itemId] = $this->app->jbmoney->toFormat($itemsPrice[$itemId], $currencyConvert);
        }

        return array(
            'items' => $itemsPrice,
            'count' => $count,
            'total' => $this->app->jbmoney->toFormat($total, $currencyConvert),
        );
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
     * @param string $key
     * @param mixed  $value
     */
    protected function _setSession($key, $value)
    {
        $session   = JFactory::getSession();
        $jbcompare = $session->get($this->_namespaceHelper, array(), $this->_namespace);

        $jbcompare[$key] = $value;

        $session->set($this->_namespaceHelper, $jbcompare, $this->_namespace);
    }

}
