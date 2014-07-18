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


Class JBModelFilter extends JBModel
{

    /**
     * Create and return self instance
     * @return JBModelFilter
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Filter search
     * @param array  $elements
     * @param string $logic
     * @param bool   $type
     * @param int    $applicationId
     * @param bool   $exact
     * @param int    $offset
     * @param int    $limit
     * @param string $order
     * @return array|JObject
     */
    public function search(
        $elements = array(), $logic = 'AND', $type = false,
        $applicationId = 0, $exact = false, $offset = 0, $limit = 0,
        $order = 'alpha'
    )
    {
        $cacheHash = sha1(serialize(func_get_args()));
        $cacheKey  = 'filter/' . $cacheHash;
        $result    = $this->app->jbcache->get($cacheHash, $cacheKey);

        if (empty($result)) {

            $order  = $this->app->jborder->get($order, 'tItem');
            $select = $this->_getSearchSelect($elements, $logic, $type, $applicationId, $exact);

            $select->limit($limit, $offset);
            $select->order($order);

            $this->_setBigSelects();

            $rows   = $this->fetchAll($select, true);
            $result = $this->_groupBy($rows, 'id');

            $this->app->jbcache->set($cacheHash, $result, $cacheKey);
        }

        $this->app->jbdebug->mark('filter::model::search');

        return $result;
    }

    /**
     * Get count for pagination
     * @param array  $elements
     * @param string $logic
     * @param bool   $type
     * @param int    $applicationId
     * @param bool   $exact
     * @return mixed
     */
    public function searchCount($elements = array(), $logic = 'AND', $type = false, $applicationId = 0, $exact = false)
    {
        $cacheHash = sha1(serialize(func_get_args()));
        $cacheKey  = 'filter-count/' . $cacheHash;
        $result    = $this->app->jbcache->get($cacheHash, $cacheKey);

        if (empty($result)) {

            $select = $this->_getSearchSelect($elements, $logic, $type, $applicationId, $exact);
            $rows   = $this->fetchAll($select, true);

            $result = count($rows);

            $this->app->jbcache->set($cacheHash, $result, $cacheKey);
        }

        $this->app->jbdebug->mark('filter::model::searchCount');

        return (int)$result;
    }

    /**
     * Create sql query for search items in database
     * @param array  $elements
     * @param string $logic
     * @param bool   $type
     * @param int    $applicationId
     * @param bool   $exact
     * @param int    $offset
     * @param int    $limit
     * @return JBDatabaseQuery
     */
    protected function _getSearchSelect(
        $elements = array(), $logic = 'AND', $type = false,
        $applicationId = 0, $exact = false, $offset = 0, $limit = 0
    )
    {
        static $select;

        if (isset($select)) {
            return clone($select);
        }

        $logic = strtoupper(trim($logic));

        $select = $this->_getItemSelect($type, $applicationId)
            ->clear('select')
            ->select(array('tItem.id AS id'))
            ->group('tItem.id');

        $conditions = array();
        if (count($elements) > 0) {
            $i = 0;

            foreach ($elements as $elementId => $value) {
                $i++;

                $modelElement = $this->app->jbentity->getElementModel(
                    $elementId, $type, $applicationId, $this->_isRange($value)
                );

                if ($logic == 'OR') {

                    $tmpCondition = $modelElement->conditionOR($select, $elementId, $value, $i, $exact);
                    if (!empty($tmpCondition)) {
                        $conditions[] = $tmpCondition;
                    }

                } else {
                    $select = $modelElement->conditionAND($select, $elementId, $value, $i, $exact);
                }
            }
        }

        if ($logic == 'OR') {
            if ($exact) {
                $select->innerJoin(ZOO_TABLE_JBZOO_INDEX . ' AS tIndex ON tItem.id = tIndex.item_id');
            } else {
                $select->innerJoin(ZOO_TABLE_SEARCH . ' AS tIndex ON tItem.id = tIndex.item_id');
            }

            $select->where("\n (" . implode("\n OR ", $conditions) . ') ');
        }

        return clone($select);
    }

    /**
     * Check is element is range
     * @param $value
     * @return bool
     */
    protected function _isRange($value)
    {
        if (is_array($value) && (isset($value['range']) || isset($value['range-date']))) {
            return true;
        }

        return false;
    }
}
