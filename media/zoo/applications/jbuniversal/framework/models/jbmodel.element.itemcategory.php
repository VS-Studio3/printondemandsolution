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


class JBModelElementItemcategory extends JBModelElement
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
        $select->innerJoin(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem ON tItem.id = tCategoryItem.item_id');
        $select->where('tCategoryItem.category_id IN (' . implode(',', $value) . ')');

        return $select;
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
        $value   = $this->_prepareValue($value);
        $itemsId = $this->_getItemIdsByCategoryIds($value);

        return 'tItem.id IN (' . implode(',', $itemsId) . ')';
    }

    /**
     * Get ItemId's by categoriesId's
     * @param $categoriesId
     * @return array
     */
    protected function _getItemIdsByCategoryIds($categoriesId)
    {

        if (!is_array($categoriesId)) {
            $categoriesId = array($categoriesId);
        }

        $select = $this->_getSelect()
            ->select('tCategoryItem.item_id')
            ->from(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategoryItem')
            ->where('tCategoryItem.category_id IN (' . implode(', ', $categoriesId) . ')');

        $result = $this->fetchAll($select);

        $result = $this->_groupBy($result, 'item_id');

        return $result;
    }


    /**
     * Prepare and validate value
     * @param $value
     * @return mixed
     */
    protected function _prepareValue($value)
    {
        if (!is_array($value)) {
            $value = array($value);
        }

        $newValue = array();

        foreach ($value as $categoryId) {
            $categoryId = (int)$categoryId;
            if ($categoryId) {
                $newValue[] = $categoryId;
            }
        }

        $value = $this->_attachSubcategories($newValue);

        return $value;
    }

    /**
     * Add subcategories ids for selected categories
     * @param $parentCategories
     * @return array
     */
    protected function _attachSubcategories($parentCategories)
    {
        if (empty($parentCategories)) {
            $parentCategories = array(0);
        }

        $select = $this->_getSelect()
            ->select('tCategory.id')
            ->from(ZOO_TABLE_CATEGORY . ' AS tCategory')
            ->where('tCategory.parent IN (' . implode(', ', $parentCategories) . ')');

        $subcategories   = $this->fetchAll($select);
        $subcategoriesId = $this->_groupBy($subcategories, 'id');

        $result = array_merge($subcategoriesId, $parentCategories);

        return $result;
    }
}