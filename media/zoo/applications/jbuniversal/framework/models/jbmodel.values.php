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


Class JBModelValues extends JBModel {
    /**
     * Create and return self instance
     * @return JBModelValues
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Get user properties values list
     * @param string $identifier
     * @param string $type
     * @param string $applicationId
     * @param array  $filter
     * @return array
     */
    public function getPropsValues($identifier, $type = null, $applicationId = null, array $filter = array())
    {
        $this->app->jbdebug->mark('model::' . $identifier . '::start');

        $cacheHash = sha1(serialize(func_get_args()));
        $cacheKey  = 'get-props-values/' . $identifier . '/' . $cacheHash;
        if (!($result = $this->app->jbcache->get($cacheHash, $cacheKey))) {

            $ids    = null;
            $tmpIds = array();
            if (!empty($filter)) {

                foreach ($filter as $filterIdentifier => $filterValue) {

                    if (!is_string($filterValue)) {
                        continue;
                    }

                    $selectIds = $this->_getItemSelect($type, $applicationId)
                            ->clear('select')
                            ->select('tIndex.item_id AS id')
                            ->innerJoin(ZOO_TABLE_JBZOO_INDEX . ' AS tIndex ON tIndex.item_id = tItem.id')
                            ->where('tIndex.element_id = ?', $filterIdentifier)
                            ->where('tIndex.value_string = ?', $filterValue);

                    $tmpIds = $this->fetchAll($selectIds);
                    $tmpIds = $this->app->jbarray->getField($tmpIds, 'id');

                    if (is_null($ids)) {
                        $ids = $tmpIds;
                    } else {
                        $ids = array_intersect($ids, $tmpIds);
                    }
                }
            }

            $select = $this->_getItemSelect($type, $applicationId)
                    ->clear('select')
                    ->select('tIndex.value_string AS value, tIndex.value_string AS text, COUNT(tIndex.item_id) AS count')
                    ->innerJoin(ZOO_TABLE_JBZOO_INDEX . ' AS tIndex ON tIndex.item_id = tItem.id')
                    ->where('tIndex.element_id = ?', $identifier)
                    ->where('tIndex.value_string IS NOT NULL')
                    ->group('tIndex.value_string')
                    ->order('tIndex.value_string ASC');

            if (is_array($ids)) {
                if (count($ids)) {
                    $select->where('tItem.id IN (' . implode(',', $ids) . ')');
                    $result = $this->fetchAll($select, true);
                } else {
                    $result = array();
                }

            } else {
                $result = $this->fetchAll($select, true);
            }

            $this->app->jbcache->set($cacheHash, $result, $cacheKey);
        }

        $this->app->jbdebug->mark('model::' . $identifier . '::end');

        return $result;
    }

    /**
     * Get authors values list
     * @param $applicationId int
     * @return array
     */
    public function getAuthorValues($applicationId)
    {
        $this->app->jbdebug->mark('model::filter::getAuthorValues:start');

        if (!($result = $this->app->jbcache->get(func_get_args(), 'get-author-values'))) {

            $select = $this->_getSelect()
                    ->select('tItem.created_by AS value, tUsers.name AS text, count(tItem.id) AS count')
                    ->from(ZOO_TABLE_ITEM . ' AS tItem')
                    ->innerJoin('#__users AS tUsers ON tUsers.id = tItem.created_by')
                    ->group('tItem.created_by')
                    ->where('tItem.application_id = ?', (int)$applicationId)
                    ->order('tUsers.name ASC');
            $result = $this->fetchAll($select, true);

            $this->app->jbcache->set(func_get_args(), $result, 'get-author-values');
        }

        $this->app->jbdebug->mark('model::filter::getAuthorValues::end');

        return $result;
    }

    /**
     * Get name values
     * @param int $applicationId
     * @return array
     */
    public function getNameValues($applicationId)
    {
        $this->app->jbdebug->mark('model::filter::getNameValues:start');

        if (!($result = $this->app->jbcache->get(func_get_args(), 'get-name-values'))) {

            $select = $this->_getItemSelect(null, $applicationId)
                    ->clear('select')
                    ->select(array('tItem.id AS value', 'tItem.name AS text', 'count(tItem.id) AS count'))
                    ->group('tItem.name')
                    ->order('tItem.name ASC');
            $result = $this->fetchAll($select, true);

            $this->app->jbcache->set(func_get_args(), $result, 'get-name-values');
        }

        $this->app->jbdebug->mark('model::filter::getNameValues::end');

        return $result;
    }

    /**
     * Get name values
     * @param int $applicationId
     * @return array
     */
    public function getTagValues($applicationId)
    {
        $this->app->jbdebug->mark('model::filter::getTagValues:start');

        if (!($result = $this->app->jbcache->get(func_get_args(), 'get-tag-values'))) {

            $select = $this->_getItemSelect(null, $applicationId)
                    ->clear('select')
                    ->select(array('tTag.name AS value', 'tTag.name AS text', 'count(tTag.name) AS count'))
                    ->innerJoin(ZOO_TABLE_TAG . ' AS tTag ON tTag.item_id = tItem.id')
                    ->group('tTag.name')
                    ->order('tTag.name ASC');
            $result = $this->fetchAll($select, true);

            $this->app->jbcache->set(func_get_args(), $result, 'get-tag-values');
        }
        $this->app->jbdebug->mark('model::filter::getTagValues::end');

        return $result;
    }

}
