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


Class JBModelAutocomplete extends JBModel
{

    /**
     * Create and return self instance
     * @return JBModelAutocomplete
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Autocomplete query
     * @param string      $query
     * @param string      $element
     * @param null|string $type
     * @param null|int    $applicationId
     * @param int         $limit
     * @return JObject
     */
    public function field($query, $element, $type = null, $applicationId = null, $limit = 10)
    {
        if (empty($query)) {
            return array();
        }

        $selectItems = $this->_getItemSelect($type, $applicationId)
                ->clear('select')
                ->select(array('tItem.id AS id'));

        $select = $this->_getSelect()
                ->select('tIndex.*')
                ->from(ZOO_TABLE_SEARCH . ' AS tIndex')
                ->innerJoin('(' . $selectItems . ') AS tItems ON tIndex.item_id = tItems.id')
                ->where('tIndex.element_id = ?', $element)
                ->where($this->_buildLikeBySpaces($query, 'tIndex.value'))
                ->group('tIndex.value')
                ->order('tIndex.value')
                ->limit($limit);

        return $this->fetchAll($select);
    }

    /**
     * Autocomplete by item name
     * @param string      $query
     * @param null|string $type
     * @param null|string $applicationId
     * @param int         $limit
     * @return null|array
     */
    public function name($query, $type = null, $applicationId = null, $limit = 20)
    {
        if (empty($query)) {
            return array();
        }

        $select = $this->_getItemSelect($type, $applicationId)
                ->clear('select')
                ->select(array('tItem.name AS value', 'tItem.id'))
                ->where($this->_buildLikeBySpaces($query, 'tItem.name'))
                ->order('tItem.name')
                ->limit($limit);

        return $this->fetchAll($select);
    }

    /**
     * Autocomplete by item tags
     * @param string      $query
     * @param null|string $type
     * @param null|string $applicationId
     * @param int         $limit
     * @return null|array
     */
    public function tag($query, $type = null, $applicationId = null, $limit = 20)
    {
        if (empty($query)) {
            return array();
        }

        $select = $this->_getItemSelect($type, $applicationId)
                ->clear('select')
                ->select(array('tTag.name AS value'))
                ->innerJoin(ZOO_TABLE_TAG . ' AS tTag ON tTag.item_id = tItem.id')
                ->where($this->_buildLikeBySpaces($query, 'tTag.name'))
                ->group('tTag.name')
                ->order('tTag.name ASC')
                ->limit($limit);

        return $this->fetchAll($select);
    }

    /**
     * Autocomplete by authors
     * @param string      $query
     * @param null|string $type
     * @param null|string $applicationId
     * @param int         $limit
     * @return null|array
     */
    public function author($query, $type = null, $applicationId = null, $limit = 20)
    {
        if (empty($query)) {
            return array();
        }

        $select = $this->_getSelect()
                ->select(array('tUsers.name AS value'))
                ->from(ZOO_TABLE_ITEM . ' AS tItem')
                ->innerJoin('#__users AS tUsers ON tUsers.id = tItem.created_by')
                ->group('tItem.created_by')
                ->where('tItem.application_id = ?', (int)$applicationId)
                ->where('tItem.type = ?', $type)
                ->where($this->_buildLikeBySpaces($query, 'tUsers.name'))
                ->order('tUsers.name ASC')
                ->limit($limit);

        return $this->fetchAll($select);
    }

}
