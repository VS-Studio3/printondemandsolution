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


Class JBModelOrder extends JBModel
{
    /**
     * Create and return self instance
     * @return JBModelValues
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Get order by itemid
     * @param $orderId
     */
    public function getById($orderId)
    {
        $orderId = (int)$orderId;
        $order   = $this->app->table->item->get($orderId);

        return $order;
    }

    /**
     * Get JBprice info from item
     * @param Item $item
     * @return null|elementJBBasketItems
     */
    public function getDetails(Item $item)
    {
        $elements = $item->getElements();

        foreach ($elements as $element) {

            if (JString::strtolower(get_class($element)) == 'elementjbbasketitems') {

                return $element;
            }
        }

        return null;
    }

}
