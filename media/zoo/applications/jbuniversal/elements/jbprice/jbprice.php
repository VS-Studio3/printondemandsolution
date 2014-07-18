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

/**
 * The Price element for JBZoo
 */
class ElementJBPrice extends ElementRepeatable implements iRepeatSubmittable
{
    /**
     * Element constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCallback('ajaxAddToCart');
        $this->registerCallback('ajaxRemoveFromCart');
        $this->registerCallback('ajaxModalWindow');
    }

    /**
     * Checks if the repeatables element's value is set.
     * @param array $params
     * @return bool
     */
    protected function _hasValue($params = array())
    {
        if ((int)$this->config->get('basket-nopaid', 0)) {

            return true;
        } else {

            $value = $this->_getSearchData();

            return !empty($value);
        }

    }

    /**
     * Get repeatable elements search data.
     * @return string Search data
     */
    protected function _getSearchData()
    {
        $value = $this->get('value', $this->config->get('default'));
        $value = $this->app->jbmoney->clearValue($value);

        return $value;
    }

    /**
     * Renders the repeatable edit form field.
     * @return string HTML
     */
    protected function _edit()
    {
        // get price value
        $value = $this->get('value', $this->config->get('default'));
        $value = $this->app->jbmoney->clearValue($value);

        $configCurrency = $this->_getCurrency();

        $html = array();

        $attrs = array(
            'size'        => '20',
            'maxlength'   => '255',
            'class'       => 'jbprice jbprice-value',
            'placeholder' => JText::_('JBZOO_PRICE_INPUT_VALUE') . ', ' . $configCurrency
        );

        $html[] = $this->app->html->_('control.text', $this->getControlName('value'), $value, $this->app->jbhtml->buildAttrs($attrs));

        $attrs = array(
            'size'        => '20',
            'maxlength'   => '255',
            'class'       => 'jbprice jbprice-description',
            'placeholder' => JText::_('JBZOO_PRICE_INPUT_DESC'),
        );

        $html[] = $this->app->html->_('control.text', $this->getControlName('description'), $this->get('description', ''), $attrs);

        return '<div>' . implode(" ", $html) . '</div>';
    }

    /**
     * Render edit
     * @return string
     */
    public function edit()
    {
        $html = array();

        $skuValue = $this->get('sku');

        $attrs = array(
            'size'        => '20',
            'maxlength'   => '255',
            'placeholder' => JText::_('JBZOO_PRICE_INPUT_SKU'),
            'style'       => 'width:100px; margin-bottom:12px;',
        );

        $html[] = $this->app->html->_('control.text', $this->getControlName('sku'), $skuValue, $this->app->jbhtml->buildAttrs($attrs));
        $html[] = '<br>';
        $html[] = '<strong>' . JText::_('JBZOO_CART_IN_STOCK') . '</strong>&nbsp;&nbsp;&nbsp;';
        $html[] = $this->app->html->_('select.booleanlist', $this->getControlName('in_stock'), '', $this->_isInStock());
        $html[] = '<br><br>';
        $html[] = $this->_renderRepeatable('_edit');

        return '<div class="jbprice-wrapper">' . implode("\n ", $html) . '</div>';
    }

    /**
     * Renders the element in submission.
     * @param array $params AppData submission parameters
     * @return string|void HTML
     */
    public function _renderSubmission($params = array())
    {
        return $this->_edit($params);
    }

    /**
     * Render action
     * @param array $params
     * @return null|string
     */
    protected function _render($params = array())
    {
        $description = $this->get('description', '');
        $value       = $this->get('value', $this->config->get('default'));
        $value       = $this->app->jbmoney->clearValue($value);

        $valueCur  = $this->_getCurrency();
        $activeCur = $this->_getActiveCur($params);

        $values = array();
        foreach ($params['currency-list'] as $currency) {
            $values[$currency] = $this->app->jbmoney->convert($valueCur, $currency, $value);
            $values[$currency] = $this->app->jbmoney->toFormat($values[$currency], $currency);
        }

        if ($layout = $this->getLayout('_jbprice.php')) {
            return self::renderLayout($layout, array(
                'params'      => $params,
                'config'      => $this->config,
                'values'      => $values,
                'description' => $description,
                'activeCur'   => $activeCur,
            ));
        }

        return null;
    }

    /**
     * Render
     * @param array $params
     * @return string
     */
    public function render($params = array())
    {
        if (!empty($params['currency-list'])) {

            $result = array();

            $summAll = 0;

            foreach ($this as $key => $self) {

                $params['counter'] = $key;

                $summAll += (float)$this->_getSearchData();

                $result[] = $this->_render($params);
            }

            $item      = $this->getItem();
            $count     = count($params['currency-list']);
            $activeCur = $this->_getActiveCur($params);
            $isInCart  = $this->app->jbcart->isExists($item);
            $modalUrl  = $this->app->jbrouter->element($this->identifier, $item->id, 'ajaxModalWindow');

            $rmFromCartUrl = $this->app->jbrouter->element($this->identifier, $item->id, 'ajaxRemoveFromCart');

            $params = $this->app->data->create($params);

            if ($layout = $this->getLayout()) {

                $html = self::renderLayout($layout, array(
                    'count'             => $count,
                    'params'            => $params,
                    'activeCur'         => $activeCur,
                    'config'            => $this->config,
                    'currencyList'      => $params->get('currency-list'),
                    'values'            => $this->app->element->applySeparators($params['separated_by'], $result),
                    'isInCart'          => $isInCart,
                    'modalUrl'          => $modalUrl,
                    'removeFromCartUrl' => $rmFromCartUrl,
                    'nopaidOrder'       => !$summAll && (int)$this->config->get('basket-nopaid', 0)
                ));

                return $html;
            }
        }

        return 'Please, select a currency';
    }

    /**
     * Ajax add to cart method
     */
    public function ajaxAddToCart($quantity = 1, $priceIndex = 0)
    {
        if ($this->_isInStock()) {
            $price = $this->_getPriceByIndex((int)$priceIndex);

            $params = array(
                'priceIndex' => (int)$priceIndex,
                'quantity'   => (int)$quantity,
                'price'      => isset($price['value']) ? $price['value'] : '',
                'priceDesc'  => isset($price['description']) ? $price['description'] : '',
                'itemId'     => $this->getItem()->id,
                'currency'   => $this->_getCurrency(),
                'sku'        => ($this->get('sku') ? $this->get('sku') : $this->getItem()->id),
            );

            $this->app->jbcart->addItem($this->getItem(), $params);
        }
        $this->app->jbajax->send();
    }

    /**
     * Ajax remove from cart method
     */
    public function ajaxRemoveFromCart()
    {
        $this->app->jbcart->removeItem($this->getItem());
        $this->app->jbajax->send();
    }

    /**
     * Show modal window
     */
    public function ajaxModalWindow()
    {
        $currency = $this->config->get('currency');

        $basketUrl = null;

        $basketMenuitem = (int)$this->config->get('basket-menuitem');
        $basketAppid    = (int)$this->config->get('basket-appid');

        if ($basketMenuitem && $basketAppid) {
            $basketUrl = $this->app->jbrouter->basket($basketMenuitem, $basketAppid);
        }

        echo self::renderLayout($this->getLayout('modal.php'), array(
            'config'       => $this->config,
            'values'       => $this->data(),
            'currency'     => $currency,
            'addToCartUrl' => $this->app->jbrouter->element($this->identifier, $this->getItem()->id, 'ajaxAddToCart'),
            'basketUrl'    => $basketUrl
        ));
    }

    /**
     * Get price by index
     * @param int $index
     * @return float|null
     */
    protected function _getPriceByIndex($index = 0)
    {
        $values = $this->data();

        if (isset($values[$index])) {
            $result = $values[$index];

        } else {
            $result = array(
                'value'       => $this->_getMainPrice(),
                'description' => '',
            );
        }

        return $result;
    }

    /**
     * Get currency from element config
     * @return mixed
     */
    protected function _getCurrency()
    {
        $currency = $this->config->get('currency', 'RUB');
        if (is_array($currency)) {
            return current($currency);
        }

        return $currency;
    }

    /**
     * Get default main price
     * @return float|null
     */
    protected function _getMainPrice()
    {
        $data  = $this->data();
        $price = null;

        if ((int)$this->config->get('repeatable')) {
            foreach ($data as $dataRow) {
                $isMain = isset($dataRow['is_main']) ? (int)$dataRow['is_main'] : false;
                if ($isMain) {
                    $price = (float)$dataRow['value'];
                }
            }
        }

        if (is_null($price)) {
            reset($data);
            $dataRow = current($data);
            $price   = (float)$dataRow['value'];
        }

        return $price;
    }

    /**
     * @param $params
     * @return mixed
     */
    protected function _getActiveCur($params = array())
    {
        $activeCur = $this->get('currency', $this->_getCurrency());

        if (!isset($params['currency-list'])) {
            $params['currency-list'] = array();
        }

        if (!in_array($activeCur, $params['currency-list'])) {
            $activeCur = current($params['currency-list']);
        }

        return $activeCur;
    }

    /**
     * Validate submition
     * @param JSONData $value
     * @param JSONData $params
     * @return array
     * @throws AppValidatorException
     */
    public function _validateSubmission($value, $params)
    {
        $description = strip_tags($value->get('description', ''));
        $valueMoney  = $this->app->jbmoney->clearValue($value->get('value', '0'));

        return array(
            'description' => $description,
            'value'       => $valueMoney,
        );
    }

    /**
     * Get relative path
     * @param $path
     * @return mixed
     */
    protected function _getRelativeAssetsPath($path)
    {
        $fullPath     = $this->app->path->path($path);
        $relativePath = $this->app->path->relative($fullPath);

        return $relativePath;
    }

    /**
     * Get item SKU
     * @return mixed
     */
    protected function _getSku()
    {
        $data = $this->data();

        $result = $this->getItem()->id;
        if (isset($data[0])) {
            $dataParam = $this->app->data->create($data[0]);
            $result    = $dataParam->get('sku') ? $dataParam->get('sku', $result) : $result;
        }

        return $result;
    }

    /**
     * Is item in stock
     * @return int
     */
    protected function _isInStock()
    {
        $data = $this->data();

        $result = 1;
        if (isset($data[0])) {
            $dataParam = $this->app->data->create($data[0]);
            $result    = (int)$dataParam->get('in_stock', $result);
        }

        return $result;
    }

}
