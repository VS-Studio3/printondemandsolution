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


class JBModelElement extends JBModel
{

    /**
     * @var string
     */
    protected $_identifier = null;

    /**
     * @var JSONData
     */
    protected $_config = null;

    /**
     * @var Element
     */
    protected $_element = null;

    /**
     * @param Element $element
     * @param         $applicationId
     */
    function __construct(Element $element, $applicationId)
    {
        parent::__construct();
        $this->_element       = $element;
        $this->_config        = $element->config;
        $this->_identifier    = $element->identifier;
        $this->_applicationId = $applicationId;
    }

    /**
     * Set AND element conditions
     * @param JBDatabaseQuery $select
     * @param string          $elementId
     * @param string|array    $value
     * @param int             $i
     * @param bool            $exact
     * @return JBDatabaseQuery
     */
    public function conditionAND(JBDatabaseQuery $select, $elementId, $value, $i = 0, $exact = false)
    {
        $value = $this->_prepareValue($value, $exact);

        if ($exact) {
            $selectInner = $this->_conditionAndExact($select, $elementId, $value);
        } else {
            $selectInner = $this->_conditionAndNoExact($select, $elementId, $value);
        }

        return $select->innerJoin('(' . $selectInner . ') AS tIndex' . $i . ' ON tItem.id = tIndex' . $i . '.item_id');
    }

    /**
     * Set OR element conditions
     * @param JBDatabaseQuery $select
     * @param string          $elementId
     * @param string|array    $value
     * @param int             $i
     * @param bool            $exact
     * @return array
     */
    public function conditionOR(JBDatabaseQuery $select, $elementId, $value, $i = 0, $exact = false)
    {
        $value = $this->_prepareValue($value, $exact);

        if ($exact) {
            $where = $this->_conditionOrExact($select, $elementId, $value);
        } else {
            $where = $this->_conditionOrNoExact($select, $elementId, $value);
        }

        return ' (' . implode(' AND ', $where) . ') ';
    }

    /**
     * @param JBDatabaseQuery $select
     * @param                 $elementId
     * @param                 $value
     * @return JBDatabaseQuery
     */
    protected function _conditionAndExact(JBDatabaseQuery $select, $elementId, $value)
    {

        $selectInner = $this->_getSelect()
                ->select('tIndex_inner.item_id AS item_id')
                ->from(ZOO_TABLE_JBZOO_INDEX . ' AS tIndex_inner')
                ->where('tIndex_inner.element_id = ?', $elementId);

        if (is_array($value)) {

            $conditions = array();
            foreach ($value as $valueOne) {
                $conditions[] = 'tIndex_inner.value_string = ' . $this->_db->quote($valueOne);
            }
            $selectInner->where('(' . implode(' OR ', $conditions) . ')');

        } else {
            $selectInner->where('tIndex_inner.value_string = ?', $value);
        }

        return $selectInner;
    }

    /**
     * @param JBDatabaseQuery $select
     * @param                 $elementId
     * @param                 $value
     * @return JBDatabaseQuery
     */
    protected function _conditionAndNoExact(JBDatabaseQuery $select, $elementId, $value)
    {
        $selectInner = $this->_getSelect()
                ->select('item_id')
                ->from(ZOO_TABLE_SEARCH . ' AS tIndex_inner')
                ->where('tIndex_inner.element_id = ?', $elementId);

        if (is_array($value)) {

            $conditions = array();
            foreach ($value as $valueOne) {
                $conditions[] = $this->_buildLikeBySpaces($valueOne, 'tIndex_inner.value');
            }

            $selectInner->where('(' . implode(' OR ', $conditions) . ')');

        } else {
            $selectInner->where($this->_buildLikeBySpaces($value, 'tIndex_inner.value'));
        }

        return $selectInner;
    }

    /**
     * @param JBDatabaseQuery $select
     * @param string          $elementId
     * @param string|array    $value
     * @return array
     */
    protected function _conditionOrExact(JBDatabaseQuery $select, $elementId, $value)
    {
        $where = array();

        if (is_array($value)) {

            $whereMulti = array();
            foreach ($value as $valueOne) {
                $whereMulti[] = 'tIndex.value_string = ' . $this->_db->quote($valueOne) . "\n";
            }
            $where[] = ' (' . implode(' OR ', $whereMulti) . ') ';

        } else {
            $where[] = 'tIndex.value_string = ' . $this->_db->quote($value);
        }

        return $where;
    }

    /**
     * @param JBDatabaseQuery $select
     * @param string          $elementId
     * @param string|array    $value
     * @return array
     */
    protected function _conditionOrNoExact(JBDatabaseQuery $select, $elementId, $value)
    {
        $where = array();

        if (is_array($value)) {

            $whereMulti = array();
            foreach ($value as $valueOne) {
                $whereMulti[] = $this->_buildLikeBySpaces($valueOne, 'tIndex.value') . "\n";
            }

            $where[] = ' (' . implode(' OR ', $whereMulti) . ') ';

        } else {
            $where[] = $this->_buildLikeBySpaces($value, 'tIndex.value');
        }

        return $where;
    }

    /**
     * Prepare value
     * @param string|array $value
     * @param boolean      $exact
     * @return mixed
     */
    protected function _prepareValue($value, $exact = false)
    {
        return $value;
    }

}