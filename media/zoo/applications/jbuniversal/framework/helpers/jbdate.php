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


class JBDateHelper extends AppHelper
{
    /**
     * @var string
     */
    public $regDate = '#^(\d{4})([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$#ius';

    /**
     * @var string
     */
    public $regDatetime = '#^(\d{4})([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])\s*([0][0-9]|[1][0-9]|[2][0-3]):([0-5][0-9]):([0-5][0-9])$#ius';

    /**
     * Validate string as date or datetime
     * @param string $date
     * @return int|null
     */
    public function convertToStamp($date)
    {
        $dates = explode("\n", JString::trim($date));

        $result = array();

        if (!empty($dates)) {
            foreach ($dates as $date) {

                if (!preg_match("#\\d{4}#", $date)) {
                    continue;
                }

                if ($time = strtotime($date)) {
                    $result[] = $this->toMysql($time);
                }
            }
        }

        return $result;
    }

    /**
     * Convert time for mysql
     * @param null|int $time
     * @return string
     */
    public function toMysql($time = null)
    {
        if (!empty($time)) {
            if (is_numeric($time)) {
                $time = (int)$time;
            } else {
                $time = strtotime($time);
            }
        }

        if ($time) {
            return date('Y-m-d H:i:s', $time);
        } else {
            return null;
        }

    }

}