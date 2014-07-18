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


Class JBModelRelated extends JBModel
{

    /**
     * Create and return self instance
     * @return JBModelRelated
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Get auto related items
     * @param Item $item
     * @param JSONData $config
     * @param JSONData $params
     * @return array
     */
    public function getRelated(Item $item, $config, $params)
    {
        $searchMethod = (int)$params->get('search-method', 2);

        $data   = $this->_getSearchData($item);
        $rows   = $this->_getFromDatabase($item, $data, $searchMethod);
        $result = $this->_sortByRelevant($rows, $params);

        return $this->getZooItemsByIds(array_keys($result));
    }

    /**
     * Sort db result by relevant
     * @param Array $rows
     * @param JSONData $params
     * @return array
     */
    protected function _sortByRelevant($rows, $params)
    {
        $resultTmp = array();
        foreach ($rows as $row) {

            if (!isset($resultTmp[$row['id']])) {
                $resultTmp[$row['id']] = array();
            } else {
                $id = $row['id'];
                unset($row['id']);
                $resultTmp[$id][] = $row;
            }
        }

        $result = array();
        foreach ($resultTmp as $itemId => $rows) {
            $result[$itemId] = 0;
            foreach ($rows as $row) {
                $result[$itemId] += array_sum($row);
            }

            if ($result[$itemId] < (int)$params->get('relevant', 5)) {
                unset($result[$itemId]);
            }
        }

        arsort($result);

        return array_slice($result, 0, (int)$params->get('count', 8), true);
    }

    /**
     * Get all item from database
     * @param Item $item
     * @param Array $data
     * @param Int $searchMethod
     * @return array
     */
    protected function _getFromDatabase(Item $item, $data, $searchMethod)
    {
        $where  = array();
        $select = array('tItem.id');

        foreach ($data as $elementId => $elemValues) {

            $elementId  = $this->_quote($elementId);

            if (empty($elemValues) && ($elemValues !== 0 || $elemValues !== "0")) {
                continue;
            }

            $elemValues = (array)$elemValues;

            if ($searchMethod == 1) {

                $elemValues = $this->_quote($elemValues);

                $select[] = '(('
                    . ' tIndex.element_id = ' . $elementId . ' AND '
                    . ' (IF(tIndex.value_string = ' . implode(', 1, 0) + IF(tIndex.value_string = ', $elemValues) . ', 1, 0))'
                    . ')) AS ' . $elementId;

            } else if ($searchMethod == 2) {

                $select[] = '(('
                    . ' tIndex.element_id = ' . $elementId . ' AND '
                    . ' (IF(tIndex.value_string LIKE "%' . implode('%", 1, 0) + IF(tIndex.value_string LIKE "%', $elemValues) . '%", 1, 0))'
                    . ')) AS ' . $elementId;

            } else if ($searchMethod == 3) {

                $select[] = '(IF(tIndex.value_string LIKE "%' . implode('%", 1, 0) + IF(tIndex.value_string LIKE "%', $elemValues) . '%", 1, 0))'
                    . ' AS ' . $elementId;
            }

            // collect where data
            $where[] = $elementId;
        }

        $select = $this->_getItemSelect()->clear('select')
            ->select($select)
            ->from(ZOO_TABLE_ITEM . ' AS tItem')
            ->innerJoin(ZOO_TABLE_JBZOO_INDEX . ' AS tIndex ON tIndex.item_id = tItem.id')
            ->where('tItem.id <> ?', $item->id)
            ->where('tItem.type = ?', $item->type)
            ->where('tIndex.element_id IN (' . implode(',', $where) . ')');

        $this->_setBigSelects();

        return $this->fetchAll($select, true);
    }

    /**
     * Get search data from item
     * @param Item $item
     * @return array
     */
    protected function _getSearchData(Item $item)
    {
        $result = array();

        $elements = $item->getElements();
        foreach ($elements as $element) {
            if ($data = $element->getSearchData()) {
                $result[$element->identifier] = $data;
            }
        }

        $itemCategories           = JBModelSearchindex::model()->getRelatedCategoryIds($item->id);
        $result['_itemcategory']  = $itemCategories;
        $result['_itemfrontpage'] = (int)in_array('0', $itemCategories, true);

        $result['_itemname']         = $item->name;
        $result['_itemauthor']       = $item->created_by;
        $result['_itemcreated']      = $item->created;
        $result['_itemmodified']     = $item->modified;
        $result['_itempublish_down'] = $item->publish_down;
        $result['_itempublish_up']   = $item->publish_up;
        $result['_itemtag']          = $item->getTags();

        return $result;
    }

}

