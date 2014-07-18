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


class JBModelElementRange extends JBModelElement
{

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
        return $select->where($this->_getWhere($value, $elementId));
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
        return $this->_getWhere($value, $elementId);
    }

    /**
     * Prepare value
     * @param $value
     * @return mixed
     */
    protected function _prepareValue($value)
    {
        if ($this->_isDate($value)) {
            $values = $value['range-date'];
        } else {
            $values = $value['range'];
        }

        if (!is_array($values)) {
            $values = explode('/', $values);
        }

        if ($this->_isDate($value)) {

            $values = array(
                $this->app->jbdate->toMysql($values[0]),
                $this->app->jbdate->toMysql($values[1]),
            );

        } else {

            $values = array(
                JString::trim($values[0]),
                JString::trim($values[1])
            );
        }

        if ($values[0] === '' && $values[1] === '') {
            return array();
        }

        return $values;
    }

    /**
     * Check is value is date
     * @param $value
     * @return bool
     */
    protected function _isDate($value)
    {
        return isset($value['range-date']);
    }

    /**
     * Get where conditions
     * @param $values
     * @param $elementId
     * @return array|null
     */
    protected function _getWhere($values, $elementId)
    {
        $isDate = $this->_isDate($values);

        $values = $this->_prepareValue($values);

        JBModelSearchindex::model()->checkColumns();

        if (!empty($values)) {

            if (strlen($values[0]) == 0 && strlen($values[1]) == 0) {
                return null;
            }

            $select = $this->_getSelect()
                    ->select('DISTINCT tJBZooIndex.item_id as id')
                    ->from(ZOO_TABLE_JBZOO_INDEX, 'tJBZooIndex')
                    ->where('tJBZooIndex.element_id = ?', $elementId);

            if ($isDate) {

                if (!empty($values[0]) && empty($values[1])) {
                    $select->where("tJBZooIndex.value_datetime >= STR_TO_DATE('" . $values[0] . "', '%Y-%m-%d %H:%i:%s')");

                } elseif (empty($values[0]) && !empty($values[1])) {
                    $select->where("tJBZooIndex.value_datetime <= STR_TO_DATE('" . $values[1] . "', '%Y-%m-%d %H:%i:%s')");

                } else {
                    $select->where('tJBZooIndex.value_datetime'
                            . " BETWEEN STR_TO_DATE('" . $values[0] . "', '%Y-%m-%d %H:%i:%s')"
                            . " AND STR_TO_DATE('" . $values[1] . "', '%Y-%m-%d %H:%i:%s')");
                }

            } else {
                if (strlen($values[0]) != 0) {
                    $select->where('tJBZooIndex.value_number >= ?', (float)$values[0]);
                }

                if (strlen($values[1]) != 0) {
                    $select->where('tJBZooIndex.value_number <= ?', (float)$values[1]);
                }
            }

            $rows   = $this->fetchAll($select);
            $result = $this->_groupBy($rows, 'id');

            if (count($rows)) {
                return 'tItem.id IN (' . implode(', ', $result) . ')';
            }

            return 'tItem.id IN (0)';
        }

        return null;
    }
}
