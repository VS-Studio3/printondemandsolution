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


Class JBModelFavorite extends JBModel
{
    /**
     * Create and return self instance
     * @return JBModelFavorite
     */
    public static function model()
    {
        return new self();
    }

    /**
     * Create table if it no exists
     */
    public function checkTable()
    {
        static $isCheck;

        if (!isset($isCheck)) {

            $tables = $this->_dbHelper->queryResultArray('SHOW FULL TABLES');

            if (!in_array(ZOO_TABLE_JBZOO_FAVORITE, $tables)) {

                $this->_dbHelper->query('CREATE TABLE `' . ZOO_TABLE_JBZOO_FAVORITE . '` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) NOT NULL,
                  `item_id` int(11) NOT NULL,
                  `date` datetime NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `user_id_item_id` (`user_id`,`item_id`),
                  KEY `user_id` (`user_id`)
                )');
            }

            $isCheck = true;
        }
    }

    /**
     * Check is row exists
     * @param Item $item
     * @param JUser $user
     * @return int|null
     */
    public function isExists(Item $item, JUser $user = null)
    {
        $this->checkTable();

        $user = ($user) ? $user : JFactory::getUser();

        $select = $this->_getSelect()
            ->select('*')
            ->from(ZOO_TABLE_JBZOO_FAVORITE . ' AS tFavorite')
            ->where('tFavorite.item_id = ?', (int)$item->id)
            ->where('tFavorite.user_id = ?', (int)$user->id)
            ->limit(1);

        $row = $this->fetchRow($select);

        return ($row) ? $row->id : null;
    }

    /**
     * Toggle item status
     * @param Item $item
     * @param JUser $user
     * @return bool|null
     */
    public function toggleItem(Item $item, JUser $user = null)
    {
        $this->checkTable();

        $user = ($user) ? $user : JFactory::getUser();

        if ($user->id) {

            if ($rowId = $this->isExists($item, $user)) {
                $this->_removeItem($rowId);

                return false;
            } else {
                $this->_addItem($item->id, $user->id);

                return true;
            }

        }

        return null;
    }

    /**
     * Get all items
     * @param JUser $user
     * @return array
     */
    public function getAllItems(JUser $user = null)
    {
        $this->checkTable();

        $user = ($user) ? $user : JFactory::getUser();

        $select = $this->_getSelect()
            ->select('*')
            ->from(ZOO_TABLE_JBZOO_FAVORITE . ' AS tFavorite')
            ->where('tFavorite.user_id = ?', (int)$user->id);

        $result = array();
        if ($rows = $this->fetchAll($select, true)) {
            foreach ($rows as $row) {
                $result[$row['item_id']] = $row;
            }
        }

        return $result;
    }

    /**
     * Remove favorite items for user
     * @param JUser $user
     */
    public function removeItems(JUser $user = null)
    {
        $this->checkTable();

        $user = ($user) ? $user : JFactory::getUser();

        $this->_dbHelper->query(
            "DELETE FROM `" . ZOO_TABLE_JBZOO_FAVORITE . "` WHERE (`user_id` = '" . (int)$user->id . "');"
        );
    }

    /**
     * Save item to favorites
     * @param Int $itemId
     * @param Int $userId
     */
    protected function _addItem($itemId, $userId)
    {
        $this->checkTable();

        $this->_dbHelper->query(
            "INSERT INTO `" . ZOO_TABLE_JBZOO_FAVORITE . "` (`user_id`, `item_id`, `date`) "
                . "VALUES ('" . (int)$userId . "', '" . (int)$itemId . "', now())"
        );
    }

    /**
     * Remove item from favorites
     * @param Int $rowId
     */
    protected function _removeItem($rowId)
    {
        $this->checkTable();

        $this->_dbHelper->query(
            "DELETE FROM `" . ZOO_TABLE_JBZOO_FAVORITE . "` WHERE (`id` = '" . (int)$rowId . "');"
        );
    }

}
