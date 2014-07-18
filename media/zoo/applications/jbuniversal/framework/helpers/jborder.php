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


class JBOrderHelper extends AppHelper
{

    /**
     * Order list
     * @var array
     */
    private $_orderings = array(
        'alpha'     => 'name ASC',
        'ralpha'    => 'name DESC',
        'date'      => 'created ASC',
        'rdate'     => 'created DESC',
        'hits'      => 'hits DESC',
        'rhits'     => 'hits ASC',
        'mdate'     => 'modified ASC',
        'rmdate'    => 'modified DESC',
        'sort'      => 'priority ASC',
        'rsort'     => 'priority DESC',
        'random'    => 'RAND()',
        'none'      => 'priority ASC'
    );

    /**
     * Get order
     * @param string      $order
     * @param null|string $context
     * @return string
     */
    function get($order, $context = null)
    {
        $order = isset($this->_orderings[$order]) ? $this->_orderings[$order] : $this->_orderings['none'];
        if ($context && $order != 'random') {
            $order = $context . '.' . $order;
        }

        return $order;
    }

    /**
     * Get order list
     * @return array
     */
    function getOrderings()
    {
        return $this->_orderings;
    }

}