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

class JBModelElementItemauthor extends JBModelElement
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
     * Get userId's by name (LIKE %%)
     * @param $name
     * @return array
     */
    protected function _getUserIdByName($name)
    {
        $select = $this->_getSelect()
            ->select('tUsers.id')
            ->from('#__users as tUsers')
            ->where('tUsers.name LIKE ?', '%' . $name . '%', 'OR')
            ->where('tUsers.id = ?', $name, 'OR');

        $users = $this->fetchAll($select);

        $result = $this->_groupBy($users, 'id');

        return $result;
    }

    /**
     * Check is user exists by userId
     * @param int $userId
     * @return bool
     */
    protected function _isUserExists($userId)
    {
        $select = $this->_getSelect()
            ->select('tUsers.id')
            ->from('#__users as tUsers')
            ->where('tUsers.id = ?', (int)$userId);

        $user = $this->fetchRow($select);

        return (isset($user->id)) ? true : false;
    }

    /**
     * Get conditions for search
     * @param $value
     * @return array
     */
    protected function _getWhere($value)
    {
        $conditions = array();

        if ($this->_isUserExists($value)) {

            $conditions[] = 'tItem.created_by = ' . (int)$value;

        } elseif (is_array($value)) {

            foreach ($value as $oneValue) {
                $userIds       = $this->_getUserIdByName($oneValue);
                $conditions[] = 'tItem.created_by IN (' . implode(', ', $userIds) . ')';
                $conditions[] = 'tItem.created_by_alias LIKE ' . $this->_db->quote('%' . $oneValue . '%');
            }

        }

        return '( ' . implode(' OR ', $conditions) . ' )';
    }
}
