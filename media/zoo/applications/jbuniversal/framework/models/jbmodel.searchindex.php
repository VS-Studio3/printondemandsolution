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


Class JBModelSearchindex extends JBModel
{

    /**
     * Create and return self instance
     * @return JBModelSearchindex
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Check columns in search index table
     * Add new fields if it not exists
     */
    public function checkColumns()
    {
        $tables = $this->_dbHelper->queryResultArray('SHOW FULL TABLES');

        if (!in_array(ZOO_TABLE_JBZOO_INDEX, $tables)) {

            $this->_dbHelper->query('CREATE TABLE `' . ZOO_TABLE_JBZOO_INDEX . '` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `item_id` INT(11) NOT NULL,
                    `element_id` VARCHAR(50) NOT NULL,
                    `value_number` DOUBLE NULL DEFAULT NULL,
                    `value_datetime` DATETIME NULL DEFAULT NULL,
                    `value_string` VARCHAR(100) NULL DEFAULT NULL COLLATE \'utf8_general_ci\',
                    PRIMARY KEY (`id`),
                    INDEX `element_id_value_number` (`element_id`, `value_number`),
                    INDEX `element_id_value_datetime` (`element_id`, `value_datetime`),
                    INDEX `value_string` (`value_string`),
                    INDEX `item_id` (`item_id`),
                    INDEX `element_id_value_string` (`element_id`, `value_string`)
                )
                COLLATE=\'utf8_general_ci\'
                ENGINE=MyISAM'
            );
        }
    }

    /**
     * Reindex database
     * @return int
     */
    public function reIndex()
    {
        ini_set('max_execution_time', 900);
        set_time_limit(900);

        $limit  = 1000;
        $offset = 0;
        $total  = 0;

        $this->dropTable();
        $this->checkColumns();

        while (true) {
            $select = $this->_getSelect()
                    ->select('*')
                    ->from(ZOO_TABLE_SEARCH . ' AS tSearchIndex')
                    ->limit($limit, $offset);

            $rows = $this->fetchAll($select);

            if (count($rows) == 0) {
                break;
            }

            $dataPack = array();
            foreach ($rows as $row) {
                $dataPack = array_merge($dataPack, $this->_getData($row));
            }

            $total += $this->_multiInsertData($dataPack);
            $offset += $limit;
        }

        $limit  = 200;
        $offset = 0;

        while (true) {
            $select = $this->_getSelect()
                    ->select('*')
                    ->from(ZOO_TABLE_ITEM . ' AS tItem')
                    ->limit($limit, $offset);

            $rows = $this->fetchAll($select);

            if (count($rows) == 0) {
                break;
            }

            $dataPack = array();
            foreach ($rows as $row) {

                $itemData = $this->_parseStdData($row);
                foreach ($itemData as $itemDataRow) {
                    $data     = $this->_getData($itemDataRow);
                    $dataPack = array_merge($dataPack, $data);
                }

            }

            $total += $this->_multiInsertData($dataPack);
            $offset += $limit;
        }

        return $total;
    }

    /**
     * Update row in search index table
     * @param mixed $row
     * @param bool  $checkTable
     * @return boolean
     */
    public function updateRow($row, $checkTable = true)
    {
        if ($checkTable) {
            $this->checkColumns();
        }

        if (is_array($row)) {
            $row = (object)$row;
        }

        $data = $this->_getData($row);
        return $this->_multiInsertData($data);
    }

    /**
     * Get data for database from serach index item
     * @param $row
     * @return array
     */
    public function _getData($row)
    {
        if ($elementType = $this->app->jbentity->getTypeByElementId($row->element_id)) {
            if ($elementType == 'textarea') {
                return array();

            } elseif ($elementType == 'country') {
                $elements   = $this->app->jbentity->getItemTypesData();
                $row->value = $this->_parseCoutries($row->value, $elements[$row->element_id]);
            }
        }

        $multiInsert = array();

        $row->value = JString::trim($row->value);

        // check is number
        $strings = explode("\n", $row->value);
        if (!empty($strings)) {
            foreach ($strings as $string) {

                $string = JString::trim($string);
                if ($string != '') {
                    if (preg_match('#^([0-9\.\,]+)#ius', $string, $matches)) {

                        $number = str_replace(',', '.', $matches[1]);
                        if (is_numeric($number)) {
                            $multiInsert[] = array('value_number' => $number);
                        }
                    }

                    $multiInsert[] = array('value_string' => $string);
                }
            }
        }

        // check date
        $times = $this->app->jbdate->convertToStamp($row->value);
        if (!empty($times)) {
            foreach ($times as $time) {
                if (!empty($time)) {
                    $multiInsert[] = array('value_datetime' => $time);
                }
            }
        }

        return $this->_getInsertData($multiInsert, $row);
    }

    /**
     * Update JBZoo index by itemId
     * @param $item Item
     * @return int
     */
    public function updateByItemId($item)
    {
        $this->removeById($item);

        $rows = array();

        $elements = $item->getElements();
        foreach ($elements as $element) {

            $rows[] = (object)array(
                'value'      => $element->getSearchData(),
                'element_id' => $element->identifier,
                'item_id'    => $item->id,
            );
        }

        $rows = array_merge($rows, $this->_parseStdData($item));

        $dataPack = array();

        foreach ($rows as $row) {
            $dataPack = array_merge($dataPack, $this->_getData($row));
        }

        return $this->_multiInsertData($dataPack);
    }

    /**
     * Remove item by it Id
     * @param Item $item
     */
    public function removeById($item)
    {
        $this->checkColumns();

        $delete = $this->_getSelect()
                ->delete(ZOO_TABLE_JBZOO_INDEX)
                ->where('item_id = ?', (int)$item->id);

        $this->_dbHelper->query((string)$delete);
    }

    /**
     * Drop JBZoo search index table
     */
    public function dropTable()
    {
        $this->_dbHelper->query('DROP TABLE `' . ZOO_TABLE_JBZOO_INDEX . '`');
    }

    /**
     * Multi insert query
     * @param array    $multiInsert
     * @param stdClass $row
     * @return array
     */
    private function _getInsertData($multiInsert, $row)
    {
        if (!empty($multiInsert)) {
            foreach ($multiInsert as $key => $item) {
                $multiInsert[$key]['item_id']    = $row->item_id;
                $multiInsert[$key]['element_id'] = $row->element_id;
            }
        }

        return $multiInsert;
    }

    /**
     * Multi insert data
     * @param array $rows
     * @return int
     */
    private function _multiInsertData(array $rows)
    {
        $count = 0;
        if (count($rows)) {
            $count = count($rows);

            $groupRows = array();

            foreach ($rows as $row) {
                $keys = array_keys($row);
                sort($keys);
                $groupRows[implode('.', $keys)][] = $row;
            }

            if (!empty($groupRows)) {
                foreach ($groupRows as $group) {
                    $this->_multiInsert($group, ZOO_TABLE_JBZOO_INDEX);
                }
            }
        }

        return $count;
    }

    /**
     * Parse coutries
     * @param $value
     * @param $element
     * @return string
     */
    private function _parseCoutries($value, $element)
    {
        $result = array();

        if (!empty($element['selectable_country'])) {

            foreach ($element['selectable_country'] as $countryISO) {

                $country = $this->app->country->isoToName($countryISO);

                if (strpos($value, $country) !== false) {
                    $result[] = JText::_($country);
                }

            }
        }

        return implode("\n", $result);
    }

    /**
     * Parse Standart item data
     * @param Item $item
     * @return array
     */
    private function _parseStdData($item)
    {
        $itemCategories = $this->getRelatedCategoryIds($item->id);
        $itemTags       = $this->_getRelatedTags($item->id);

        $result = array(
            (object)array(
                'element_id' => '_itemauthor',
                'item_id'    => $item->id,
                'value'      => $item->created_by,
            ),
            (object)array(
                'element_id' => '_itemcategory',
                'item_id'    => $item->id,
                'value'      => implode("\n", $itemCategories),
            ),
            (object)array(
                'element_id' => '_itemcreated',
                'item_id'    => $item->id,
                'value'      => $item->created,
            ),
            (object)array(
                'element_id' => '_itemfrontpage',
                'item_id'    => $item->id,
                'value'      => (int)in_array('0', $itemCategories, true),
            ),
            (object)array(
                'element_id' => '_itemmodified',
                'item_id'    => $item->id,
                'value'      => $item->modified,
            ),
            (object)array(
                'element_id' => '_itemname',
                'item_id'    => $item->id,
                'value'      => $item->name,
            ),
            (object)array(
                'element_id' => '_itempublish_down',
                'item_id'    => $item->id,
                'value'      => $item->publish_down,
            ),
            (object)array(
                'element_id' => '_itempublish_up',
                'item_id'    => $item->id,
                'value'      => $item->publish_up,
            ),
            (object)array(
                'element_id' => '_itemtag',
                'item_id'    => $item->id,
                'value'      => implode("\n", $itemTags),
            ),
        );

        return $result;
    }

    /**
     * Get related category id list
     * @param int $itemId
     * @return array
     */
    public function getRelatedCategoryIds($itemId)
    {
        $select = $this->_getSelect()
                ->select('tCategory.category_id AS category_id')
                ->from(ZOO_TABLE_CATEGORY_ITEM . ' AS tCategory')
                ->where('tCategory.item_id = ?', $itemId);

        $rows = $this->fetchAll($select);

        $result = array();
        foreach ($rows as $row) {
            $result[] = $row->category_id;
        }

        return $result;
    }

    /**
     * Get related tags
     * @param int $itemId
     * @return array
     */
    private function _getRelatedTags($itemId)
    {
        $select = $this->_getSelect()
                ->select('tTags.name AS name')
                ->from(ZOO_TABLE_TAG . ' AS tTags')
                ->where('tTags.item_id = ?', $itemId);

        $rows = $this->fetchAll($select);

        $result = array();
        foreach ($rows as $row) {
            $result[] = $row->name;
        }

        return $result;
    }

}
