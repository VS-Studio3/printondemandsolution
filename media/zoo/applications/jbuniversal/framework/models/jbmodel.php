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


Class JBModel
{
    /**
     * @var string
     */
    protected $_dbNull = null;

    /**
     * @var string
     */
    protected $_dbNow = null;

    /**
     * @var JDatabaseMySQLi
     */
    protected $_db = null;

    /**
     * @var DatabaseHelper
     */
    protected $_dbHelper = null;

    /**
     * Constructor
     */
    protected function __construct()
    {
        $this->app = App::getInstance('zoo');

        $this->_db       = JFactory::getDbo();
        $this->_dbHelper = $this->app->database;
        $this->_dbNow    = $this->_db->quote($this->app->date->create()->toSql());
        $this->_dbNull   = $this->_db->quote($this->_db->getNullDate());
    }

    /**
     * Create and return self instance
     * @return JBModel
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Get database query object
     * @return JBDatabaseQuery
     */
    protected function _getSelect()
    {
        return new JBDatabaseQuery($this->_db);
    }

    /**
     * Fetch one row
     * @param JBDatabaseQuery $select
     * @param bool            $toArray
     * @return JObject
     */
    public function fetchRow(JBDatabaseQuery $select, $toArray = false)
    {
        return $this->_query($select, true, $toArray);
    }

    /**
     * Fetch all query result
     * @param JBDatabaseQuery $select
     * @param bool            $toArray
     * @return array|JObject
     */
    public function fetchAll(JBDatabaseQuery $select, $toArray = false)
    {
        return $this->_query($select, false, $toArray);
    }

    /**
     * Query to database
     * @param JBDatabaseQuery $select
     * @param bool            $isOne
     * @param bool            $toArray
     * @return mixed
     */
    protected function _query(JBDatabaseQuery $select, $isOne = false, $toArray = false)
    {
        $selectSql = (string)$select;
        $this->app->jbdebug->sql($selectSql);
        $this->_db->setQuery($selectSql);

        if (!$toArray) {
            if ((boolean)$isOne) {
                $result = $this->_db->loadObject();
            } else {
                $result = $this->_db->loadObjectList();
            }

        } else {

            if ((boolean)$isOne) {
                $result = $this->_db->loadAssoc();
            } else {
                $result = $this->_db->loadAssocList();
            }
        }

        return $result;
    }

    /**
     * Get database query object for item
     * @param null|string     $type
     * @param null|string|int $applicationId
     * @return JBDatabaseQuery
     */
    protected function _getItemSelect($type = null, $applicationId = null)
    {
        $select = $this->_getSelect()
            ->select('tItem.*')
            ->from(ZOO_TABLE_ITEM . ' AS tItem')
            ->where('tItem.searchable = ?', 1)
            ->where('tItem.' . $this->app->user->getDBAccessString())
            ->where('tItem.state = ?', 1)
            ->where('(tItem.publish_up = ' . $this->_dbNull . ' OR tItem.publish_up <= ' . $this->_dbNow . ')')
            ->where('(tItem.publish_down = ' . $this->_dbNull . ' OR tItem.publish_down >= ' . $this->_dbNow . ')');

        if (is_array($type)) {
            $select->where('tItem.type IN ("' . implode('", "', $type) . '")');

        } elseif (is_string($type)) {
            $select->where('tItem.type = ?', $type);
        }

        if ((int)$applicationId) {
            $select->where('tItem.application_id = ?', (int)$applicationId);
        }

        return $select;
    }

    /**
     * Get zoo items by IDs
     * @param array  $ids
     * @param string $order
     * @return array
     */
    public function getZooItemsByIds($ids, $order = null)
    {
        if (empty($ids)) {
            return array();
        }

        $conditions = array(
            'id IN (' . implode(',', $ids) . ')'
        );

        $order  = $this->app->jborder->get($order);
        $result = $this->app->table->item->all(compact('conditions', 'order'));

        $this->app->jbdebug->mark('model::getZooItemsByIds');

        return $result;
    }

    /**
     * Set internal mysql value
     * TODO remove this hack
     */
    protected function _setBigSelects()
    {
        $this->_db->setQuery('SET SQL_BIG_SELECTS = 1');
        $this->_db->query();
        $this->app->jbdebug->mark('model::setBigSelects');
    }

    /**
     * Group array by key
     * @param array  $rows
     * @param string $key
     * @return array
     */
    protected function _groupBy($rows, $key = 'id')
    {
        $result = array();

        if (!empty($rows)) {
            foreach ($rows as $row) {

                if (is_array($row)) {
                    $value = $row[$key];
                } else if (is_object($row)) {
                    $value = $row->$key;
                } else {
                    $value = $row;
                }

                $result[$value] = $value;
            }
        }

        return $result;
    }

    /**
     * Trancate table
     * @param $tableName
     * @return mixed
     */
    protected function trancate($tableName)
    {
        return $this->_dbHelper->query('TRUNCATE `' . $tableName . '`;');
    }

    /**
     * Quote string
     * @param $vars
     * @return string
     */
    protected function _quote($vars)
    {
        if (is_array($vars)) {
            foreach ($vars as $rowKey => $rowItem) {
                $vars[$rowKey] = $this->_quote($rowItem);
            }
        } else {
            $vars = $this->_db->quote($vars);
        }

        return $vars;
    }

    /**
     * Multi insert
     * @param array  $data
     * @param string $table
     * @return mixed
     */
    protected function _multiInsert($data, $table)
    {
        if (empty($data)) {
            return false;
        }

        $keys = array_keys(current($data));

        foreach ($keys as $num => $key) {
            $keys[$num] = $table . '.' . $key;
        }

        $value_titles = '(' . implode(', ', $keys) . ")\n";

        $preValues = array();
        foreach ($data as $values) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->_quote($value);
            }

            $preValues[] = "(" . implode(", ", $values) . ")\n";
        }

        $insertedValues = implode(",\n", $preValues);

        $query = 'INSERT INTO ' . $table . ' ' . $value_titles . ' VALUES ' . $insertedValues;

        return $this->_dbHelper->query($query);
    }

    /**
     * Separate values by spaces
     * @param $value
     * @return array
     */
    protected function _separateValue($value)
    {
        $values = explode(' ', $value);

        foreach ($values as $key => $value) {

            $value = JString::trim($value);
            if (JString::strlen($value)) {
                $values[$key] = $value;
            } else {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * Build where like conditions from strings with spaces
     * @param string $value
     * @param string $fieldName
     * @return string
     */
    protected function _buildLikeBySpaces($value, $fieldName)
    {
        $values = $this->_separateValue($value);

        foreach ($values as $key => $value) {
            $values[$key] = $this->_db->quote('%' . $value . '%');
        }

        return '(' . $fieldName . ' LIKE ' . implode(' AND ' . $fieldName . ' LIKE ', $values) . ' )';
    }
}
