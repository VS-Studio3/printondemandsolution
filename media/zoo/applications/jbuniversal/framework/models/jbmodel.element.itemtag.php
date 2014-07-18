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

class JBModelElementItemtag extends JBModelElement
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
        return $this->_getWhere($value);
    }

    /**
     * Get conditions for search
     * @param $value
     * @return array
     */
    protected function _getWhere($value)
    {
        $value   = $this->_prepareValue($value);
        $itemIds = $this->_getItemIdByTag($value);

        if (empty($itemIds)) {
            $itemIds = array(0);
        }

        $conditions = 'tItem.id IN (' . implode(', ', $itemIds) . ')';

        return $conditions;
    }

    /**
     * Get itemId's by tag
     * @param string|array $name
     * @return array
     */
    protected function _getItemIdByTag($name)
    {
        $select = $this->_getSelect()
            ->select('tTags.item_id')
            ->from(ZOO_TABLE_TAG . ' AS tTags')
            ->group('tTags.item_id');

        if (is_array($name)) {
            foreach ($name as $oneName) {
                $select->where('tTags.name LIKE ?', '%' . $oneName . '%', 'OR');
            }

        } else {
            $select->where('tTags.name LIKE ?', '%' . $name . '%');
        }

        $items  = $this->fetchAll($select);
        $result = $this->_groupBy($items, 'item_id');

        return $result;
    }

}
