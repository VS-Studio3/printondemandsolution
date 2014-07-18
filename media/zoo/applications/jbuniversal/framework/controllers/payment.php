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

require_once dirname(__FILE__) . '/base.php';

class paymentJBUniversalController extends baseJBUniversalController
{

    const TYPE_ROBOX  = 'Robokassa.ru';
    const TYPE_IKASSA = 'Interkassa.com';

    /**
     * @var Int
     */
    public $appId = null;

    /**
     * @var Item
     */
    public $order = null;

    /**
     * @var Int
     */
    public $orderId = null;

    /**
     * @var Int
     */
    public $itemId = null;

    /**
     * @var ElementJBBasketItems
     */
    public $orderDetails = null;

    /**
     * @var AppTemplate
     */
    public $template = null;

    /**
     * @var ParameterData
     */
    public $appParams = null;

    /**
     * @var JBUniversalApplication
     */
    public $application = null;

    /**
     * @var JBModelOrder
     */
    public $orderModel = null;

    /**
     * @var BasketRenderer
     */
    public $renderer = null;

    /**
     * @var String
     */
    public $systemType = null;

    /**
     * Init controller
     * @param string $types
     * @throws PaymentJBUniversalControllerException
     */
    protected function _init($types = '')
    {
        parent::_init($types);

        $this->orderId = (int)$this->_jbreq->get('order_id');
        $this->appId   = (int)$this->_jbreq->get('app_id');

        $this->appParams = $this->application->getParams();

        if ($invId = (int)$this->_jbreq->get('InvId')) {
            $this->systemType = self::TYPE_ROBOX;
            $this->orderId    = $invId;

        } else if ($ikPaymentId = (int)$this->_jbreq->get('ik_payment_id')) {
            $this->systemType = self::TYPE_IKASSA;
            $this->orderId    = $ikPaymentId;
        }

        if (!$this->appId) {
            throw new PaymentJBUniversalControllerException('Applciation id is no set');
        }

        if (!$this->template = $this->application->getTemplate()) {
            throw new PaymentJBUniversalControllerException('No template selected');
        }

        if ((int)$this->appParams->get('global.jbzoo_cart_config.enable', 0) == 0) {
            throw new PaymentJBUniversalControllerException('Application is not a basket');
        }

        if ((int)$this->appParams->get('global.jbzoo_cart_config.payment-enabled') == 0) {
            throw new PaymentJBUniversalControllerException('Payment is not enabled');
        }

        if ($this->orderId) {

            $this->orderModel = JBModelOrder::model();
            if (!$this->order = $this->orderModel->getById($this->orderId)) {
                throw new PaymentJBUniversalControllerException('Order #' . $this->orderId . ' no exists');
            }

            if (!$this->orderDetails = $this->orderModel->getDetails($this->order)) {
                throw new PaymentJBUniversalControllerException('This type don\'t have JBPrice element');
            }
        }

        // set renderer
        $this->renderer = $this->app->renderer->create('basket')->addPath(array(
            $this->app->path->path('component.site:'),
            $this->template->getPath()
        ));
    }

    /**
     * Index action
     */
    function index()
    {
        if ((int)JFactory::getConfig()->get('debug') == 0) {
            error_reporting(0);
        }

        // init
        $this->_init('payment');

        $itemId = (int)$this->_jbreq->get('itemId');

        $totalSumm         = $this->orderDetails->getTotalPrice();
        $totalSummFormated = $this->orderDetails->getTotalPrice(true);

        $this->payments = array();

        if ($this->orderDetails->getOrderStatus() == ElementJBBasketItems::ORDER_STATUS_PAID) {
            throw new PaymentJBUniversalControllerException('Order has already been paid');
        }
        if ($totalSumm == 0) {
            throw new PaymentJBUniversalControllerException('To pay for the cost should be greater than zero');
        }

        // robox
        if ((int)$this->appParams->get('global.jbzoo_cart_config.robox-enabled', 0)) {

            $params               = new stdClass();
            $params->login        = JString::trim($this->appParams->get('global.jbzoo_cart_config.robox-login'));
            $params->password1    = JString::trim($this->appParams->get('global.jbzoo_cart_config.robox-password1'));
            $params->hash         = md5(implode(':', array($params->login, $totalSumm, $this->orderId, $params->password1)));
            $params->summ         = $totalSumm;
            $params->orderId      = $this->orderId;
            $params->summFormated = $totalSummFormated;
            $params->debug        = (int)$this->appParams->get('global.jbzoo_cart_config.robox-debug', 0);

            $this->payments['robox'] = $this->app->data->create($params);
        }

        // ikassa
        if ((int)$this->appParams->get('global.jbzoo_cart_config.ikassa-enabled', 0)) {
            $params               = new stdClass();
            $params->shopid       = JString::trim($this->appParams->get('global.jbzoo_cart_config.ikassa-shopid'));
            $params->summ         = $totalSumm;
            $params->orderId      = $this->orderId;
            $params->summFormated = $totalSummFormated;

            $this->payments['ikassa'] = $this->app->data->create($params);
        }

        // display
        $this->getview('payment')->addTemplatePath($this->template->getPath())->setLayout('payment')->display();
    }

    /**
     * @throws PaymentJBUniversalControllerException
     */
    public function paymentCallback()
    {
        $this->_init();

        if ($this->orderDetails->getOrderStatus() == ElementJBBasketItems::ORDER_STATUS_PAID) {
            throw new PaymentJBUniversalControllerException('Order has already been paid');
        }

        $totalsumm = $this->orderDetails->getTotalPrice();

        if ($this->systemType == self::TYPE_ROBOX) {

            $password2 = JString::trim($this->appParams->get('global.jbzoo_cart_config.robox-password2'));
            $crc       = strtoupper($_REQUEST["SignatureValue"]);
            $myCrc     = strtoupper(md5(implode(':', array($totalsumm, $this->orderId, $password2))));

            if ($crc === $myCrc) {

                // get request vars
                $args = array(
                    'date'            => $this->app->date->create()->toSQL(),
                    'system'          => $this->systemType,
                    'additionalState' => null
                );

                // execute callback method
                $this->orderDetails->callback('paymentCallback', $args);

                jexit('OK' . $this->orderId);

            } else {
                throw new PaymentJBUniversalControllerException('No valid hash');
            }

        } else if ($this->systemType == self::TYPE_IKASSA) {

            $myCrcData = implode(':', array(
                $this->_jbreq->get('ik_shop_id', ''),
                $this->_jbreq->get('ik_payment_amount', ''),
                $this->_jbreq->get('ik_payment_id', ''),
                $this->_jbreq->get('ik_paysystem_alias', ''),
                $this->_jbreq->get('ik_baggage_fields', ''),
                $this->_jbreq->get('ik_payment_state', ''),
                $this->_jbreq->get('ik_trans_id', ''),
                $this->_jbreq->get('ik_currency_exch', ''),
                $this->_jbreq->get('ik_fees_payer', ''),
                JString::trim($this->appParams->get('global.jbzoo_cart_config.ikassa-key'))
            ));

            $myCrc         = strtoupper(md5($myCrcData));
            $crc           = strtoupper($this->_jbreq->get('ik_sign_hash'));
            $shopid        = $this->appParams->get('global.jbzoo_cart_config.ikassa-shopid');
            $requestShopid = $this->_jbreq->get('ik_shop_id');
            $totalSumm     = (float)$this->orderDetails->getTotalPrice();
            $requestAmount = (float)$this->_jbreq->get('ik_payment_amount');

            if ($crc === $myCrc &&
                $totalSumm == $requestAmount &&
                $requestShopid === $shopid
            ) {
                // get request vars
                $args = array(
                    'date'            => $this->app->date->create()->toSQL(),
                    'system'          => $this->systemType,
                    'additionalState' => $this->_jbreq->get('ik_payment_state')
                );

                // execute callback method
                $this->orderDetails->callback('paymentCallback', $args);

                jexit('OK' . $this->orderId);

            } else {
                throw new PaymentJBUniversalControllerException('No valid hash');
            }

        } else {
            throw new PaymentJBUniversalControllerException('Unknown system');
        }
    }

    /**
     * Payment success page
     */
    public function paymentSuccess()
    {
        $this->_init('payment');

        // display
        $this->getview('payment_success')->addtemplatepath($this->template->getpath())->setlayout('payment_success')->display();
    }

    /**
     * Payment fail page
     */
    public function paymentFail()
    {
        $this->_init('payment');

        // display
        $this->getview('payment_fail')->addtemplatepath($this->template->getpath())->setlayout('payment_fail')->display();
    }
}

class PaymentJBUniversalControllerException extends AppException
{
}
