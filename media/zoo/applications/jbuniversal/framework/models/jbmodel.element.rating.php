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


class JBModelElementRating extends JBModelElement
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
        $value = $this->_prepareValue($value);

        return $select->where($this->_getWhere($value));
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
        $value = $this->_prepareValue($value);

        return $this->_getWhere($value);
    }

    /**
     * Prepare and validate value
     * @param string|array $value
     * @return string|array
     */
    protected function _prepareValue($value)
    {
        $values    = explode('/', $value);
        $values[0] = (int)trim($values[0]);
        $values[1] = (int)trim($values[1]);

        return $values;
    }

    /**
     * Get conditions for search
     * @param string|array $value
     * @return string
     */
    protected function _getWhere($value)
    {

        if ($value[0] == 0 && $this->_config->get('stars') == $value[1]) {
            return null;
        }

        $result = '';

        $select = $this->_getItemSelect()
            ->clear('select')
            ->select('tItem.id AS id, AVG(value) AS rate')
            ->innerJoin(ZOO_TABLE_RATING . ' AS tRating ON tRating.item_id = tItem.id')
            ->where('element_id = ?', $this->_identifier)
            ->group('tItem.id')
            ->having('rate >= ?', $value[0])
            ->having('rate <= ?', $value[1]);

        $result = $this->fetchAll($select);
        $result = $this->_groupBy($result, 'id');

        if (!empty($result)) {
            return 'tItem.id IN (' . implode(', ', $result) . ')';
        }

        return 'tItem.id IN (0)';
    }

}