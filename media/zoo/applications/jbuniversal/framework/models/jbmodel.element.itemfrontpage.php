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


class JBModelElementItemfrontpage extends JBModelElement
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
     * Get where conditions
     * @param $value
     * @return string
     */
    protected function _getWhere($value)
    {
        if ((int)$value) {
            $itemsId = $this->_getItemIdsByCategoryIds((int)$value);
            return 'tItem.id IN (' . implode(',', $itemsId) . ')';
        }

        return '1';
    }

    /**
     * Get ItemId's by categoriesId's
     * @param $value
     * @return array|JObject
     */
    protected function _getItemIdsByCategoryIds($value)
    {
        $select = $this->_getSelect()
            ->select('tCategoryItem.item_id')
            ->from(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem')
            ->innerJoin(ZOO_TABLE_ITEM . ' AS tItem ON tItem.id = tCategoryItem.item_id')
            ->where('tCategoryItem.category_id = 0')
            ->where('tItem.application_id = ?', $this->_applicationId);

        $result = $this->fetchAll($select);
        $result = $this->_groupBy($result, 'item_id');

        return $result;
    }

}
