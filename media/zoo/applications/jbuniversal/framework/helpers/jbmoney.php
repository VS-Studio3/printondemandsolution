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


class JBMoneyHelper extends AppHelper
{

    static $currencyList = null;
    static $formatList = null;

    /**
     * @var array
     */
    protected $_config = array();

    /**
     * @param App $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->_init();
    }

    /**
     * Get all currency values and cache in memory
     */
    protected function _init()
    {
        if (is_null(self::$currencyList)) {

            $xml = simplexml_load_file($this->app->path->path('jbconfig:jbcurrency.xml'));

            $this->_config = array(
                'load_from_service' => (string)$xml->config->load_from_service,
                'service_url'       => (string)$xml->config->service_url,
            );

            self::$formatList = array();
            foreach ($xml->formatlist->children() as $name => $format) {
                self::$formatList[$name] = array();
                foreach ($format->attributes() as $key => $value) {
                    self::$formatList[$name][$key] = (string)$value;
                }
            }

            $serviceValues = $this->_loadFromService();

            self::$currencyList = array();
            foreach ($xml->curencylist->children() as $code => $currency) {

                $code = JString::strtoupper($code);

                self::$currencyList[$code] = array(
                    'name' => JString::trim((string)$currency),
                );

                foreach ($currency->attributes() as $key => $value) {
                    self::$currencyList[$code][$key] = (string)$value;
                }


                if ((int)$this->_config['load_from_service'] > 0 && !empty($serviceValues)) {
                    foreach (self::$currencyList as $code => $data) {
                        $data['value'] = isset($serviceValues[$code]) ? $serviceValues[$code] : 1;

                        self::$currencyList[$code] = $data;
                    }
                }

                self::$currencyList[$code]['value'] = $this->clearValue(self::$currencyList[$code]['value']);

                if (self::$currencyList[$code]['nominal']) {
                    self::$currencyList[$code]['normValue'] = self::$currencyList[$code]['value'] / self::$currencyList[$code]['nominal'];
                } else {
                    self::$currencyList[$code]['normValue'] = 0;
                }
            }

        }
    }

    /**
     * Load currency values from service
     * @return mixed
     */
    protected function _loadFromService()
    {
        $result = array();

        $cacheKey = 'currency-' . date('d-m-Y') . '-' . $this->_config['service_url'];
        $result   = $this->app->jbcache->get($cacheKey, 'currency', true);

        if ((int)$this->_config['load_from_service'] > 0 && $this->_config['service_url']) {

            if ($xml = simplexml_load_file($this->_config['service_url'])) {

                foreach ($xml as $row) {
                    $value   = $this->clearValue('' . $row->Value);
                    $nominal = (string)$row->Nominal;
                    $code    = (string)$row->CharCode;

                    if ($nominal) {
                        $result[$code] = $value;
                    } else {
                        $result[$code] = 1;
                    }
                }

                $this->app->jbcache->set($cacheKey, $result, 'currency', true);
            }
        }

        return $result;
    }


    /**
     * Clear price string
     * @param $value
     * @return mixed|string
     */
    public function clearValue($value)
    {
        $value = (string)$value;
        $value = JString::trim($value);
        $value = preg_replace('#[^0-9\,\.]#ius', '', $value);
        $value = (float)str_replace(',', '.', $value);

        return $value;
    }

    /**
     * Convert currency
     * @param $from    string
     * @param $to      string
     * @param $value   float
     * @return mixed
     */
    public function convert($from, $to, $value)
    {
        $value = $this->clearValue($value);

        $from = JString::trim(JString::strtoupper($from));
        $to   = JString::trim(JString::strtoupper($to));

        $result = 0;

        if (isset(self::$currencyList[$to]) && isset(self::$currencyList[$from])) {
            $valueRub = self::$currencyList[$from]['normValue'] * $value;
            $result   = $valueRub / self::$currencyList[$to]['normValue'];
        }

        return $result;
    }

    /**
     * Currency list
     * @return array
     */
    public function getCurrencyList()
    {

        $result = array();
        foreach (self::$currencyList as $code => $currency) {
            $result[$code] = $currency['name'] . ' (' . $code . ')';
        }

        return $result;
    }

    /**
     * convert number to money formated string
     * @param $value
     * @param $code
     * @return null|string
     */
    public function toFormat($value, $code)
    {

        $code  = JString::trim(JString::strtoupper($code));
        $value = $this->clearValue($value);

        if (isset(self::$currencyList[$code])) {

            $params = self::$currencyList[$code];

            $formatNum = $params['format'];

            $formatParams = self::$formatList['default'];
            if (isset(self::$formatList['format_' . $formatNum])) {
                $formatParams = self::$formatList['format_' . $formatNum];
            }

            $formatedNumber = number_format($value, $formatParams['decimals'], $formatParams['dec_point'], $formatParams['thousands_sep']);

            return (!empty($params['prefix']) ? $params['prefix'] : '')
                . $formatedNumber
                . (!empty($params['postfix']) ? ' ' . $params['postfix'] : '');
        }

        return null;

    }

}
