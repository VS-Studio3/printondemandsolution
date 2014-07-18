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

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');

class ElementJBBasketItems extends Element implements iSubmittable
{

    const ORDER_STATUS_PAID   = 'paid';
    const ORDER_STATUS_NOPAID = 'nopaid';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCallback('paymentCallback');
    }

    /**
     * @param array $params
     * @return bool
     */
    public function hasValue($params = array())
    {
        return count($this->data());
    }

    /**
     * Show basket items in admin panel
     * @return mixed
     */
    public function edit()
    {
        $basketItems = $this->data();

        if (!empty($basketItems)) {

            $searchModel = JBModelFilter::model();
            $items       = $searchModel->getZooItemsByIds(array_keys($basketItems));

            if (!empty($items) && $layout = $this->getLayout('jbbasketitems.php')) {
                return self::renderLayout($layout, array(
                    'items'       => $items,
                    'basketItems' => $basketItems,
                    'params'      => isset($this->params) ? $this->params : null,
                ));
            }
        }

        return '<p>' . JText::_('JBZOO_CART_ITEMS_NOT_FOUND') . '</p>';
    }

    /**
     * Render action
     * @param array $params
     * @return mixed|string
     * @throws JException
     */
    public function render($params = array())
    {
        $params       = $this->app->data->create($params);
        $this->params = $params;
        $template     = $params->get('template', 'default');

        $summa = $this->getTotalPrice();

        if ($template == 'default' || $template == 'table') {
            return $this->edit();

        } else if ($template == 'totalprice') {
            return $this->getTotalPrice(true);

        } else if ($template == 'method') {
            return $this->getPaymentType();

        } else if ($template == 'status') {
            if ($summa) {
                return '<span class="order-status ' . $this->getOrderStatus(false) . '">' . $this->getOrderStatus(true) . '</span>';
            } else {
                return JText::_('JBZOO_PAYMENT_STATUS_PAID');
            }

        } else if ($template == 'paymentlink') {

            if ($this->getOrderStatus() == self::ORDER_STATUS_NOPAID && $summa) {

                $appId = $this->app->zoo->getApplication()->id;
                $href  = $this->app->jbrouter->basketPayment($params->get('basket-menuitem'), $appId, $this->getItem()->id);

                $html   = array();
                $html[] = '<p><input type="button" style="display:inline-block;" class="jsGoToPayment-' . $this->getItem()->id . ' add-to-cart" value="' . JText::_('JBZOO_PAYMENT_LINKTOFORM') . '" /></p>';
                $html[] = '<script type="text/javascript">';
                $html[] = 'jQuery(function($){ $(".jsGoToPayment-' . $this->getItem()->id . '").click(function(){ window.location.href = "' . $href . '"; }); });';
                $html[] = '</script>';

                return implode("\n", $html);
            }
        }
    }

    /**
     * Render submission
     * @param array $params
     * @return string
     */
    public function renderSubmission($params = array())
    {
        return '<input type="hidden" name="' . $this->getControlName('value') . '" value="_jbbaskteitems_" />';
    }

    /**
     * Validate submission
     * @param $value
     * @param $params
     * @return mixed
     * @throws JException
     */
    public function validateSubmission($value, $params)
    {

        $items = $this->app->jbcart->getAllItems();

        if (empty($items)) {
            throw new JException(JText::_('JBZOO_CART_VALIDATE_EMPTY_BASKET'));
        }

        foreach ($items as $key => $item) {

            $item = $this->app->table->item->get($item['itemId']);

            $items[$key]['name'] = $item->name;

        }

        return $items;
    }

    /**
     * Get total price
     */
    public function getTotalPrice($isFormated = false)
    {
        //return 5;

        $basketItems = $this->data();

        $i        = 0;
        $summa    = 0;
        $count    = 0;
        $currency = '';

        if (!empty($basketItems)) {

            $searchModel = JBModelFilter::model();
            $items       = $searchModel->getZooItemsByIds(array_keys($basketItems));

            foreach ($items as $item) {

                $basketInfo = $basketItems[$item->id];
                $count += $basketInfo['quantity'];

                $currency = $basketInfo['currency'];

                $subtotal = $basketInfo['quantity'] * $basketInfo['price'];
                $summa += $subtotal;
            }

            if ($isFormated) {
                return $this->app->jbmoney->toFormat($summa, $currency);
            }

            return $summa;
        }

        return null;
    }

    /**
     * Ajax call - paymentCallback
     */
    public function paymentCallback($date, $system = null, $additionalStatus = null)
    {
        $id        = $this->_getFirstElementId();
        $firstData = $this->get($id);

        if (!isset($firstData['order_info']['payment_date'])) {

            $firstData['order_info'] = array();

            $firstData['order_info']['payment_date']      = $date;
            $firstData['order_info']['payment_system']    = $system;
            $firstData['order_info']['status']            = self::ORDER_STATUS_PAID;
            $firstData['order_info']['additional_status'] = $additionalStatus;

            $this->set($id, $firstData);

            //save item
            $this->app->table->item->save($this->getItem());
        }
    }

    /**
     * @return Int
     */
    protected function _getFirstElementId()
    {
        $basketItems = $this->data();

        $itemsId = array_keys($basketItems);
        reset($itemsId);
        $firstElement = current($itemsId);

        return $firstElement;
    }

    /**
     * Get current order status
     * @param bool $isFormated
     * @return mixed
     */
    public function getOrderStatus($isFormated = false)
    {
        $id        = $this->_getFirstElementId();
        $firstData = $this->get($id);

        $status = self::ORDER_STATUS_NOPAID;
        if (isset($firstData['order_info']['status'])) {
            $status = $firstData['order_info']['status'];
        }

        if ($isFormated) {
            return JText::_('JBZOO_PAYMENT_STATUS_' . JString::strtoupper($status));
        }

        return $status;
    }

    /**
     * Get payment data
     * @return null
     */
    public function getPaymentData()
    {
        $id        = $this->_getFirstElementId();
        $firstData = $this->get($id);

        return isset($firstData['order_info']) ? $firstData['order_info'] : null;
    }

    /**
     * Get payment data
     * @return null
     */
    public function getPaymentType()
    {
        $id        = $this->_getFirstElementId();
        $firstData = $this->get($id);

        return isset($firstData['order_info']['payment_system']) ? $firstData['order_info']['payment_system'] : null;
    }
}
