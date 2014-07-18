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


class JBSelectCascadeHelper extends AppHelper
{

    /**
     * Get item list
     * @param string $names
     * @param string $items
     * @return array
     */
    public function getItemList($names, $items)
    {
        $configNames = $this->app->jbelement->parseLines($names);
        $configItems = $this->app->jbelement->parseLines($items);

        $maxLevel    = 0;
        $resultItems = array();

        if (!empty($configItems)) {

            $prevLevel     = 0;
            $prevLevelName = '';

            $nestedKeys = array();

            foreach ($configItems as $configItem) {

                if (preg_match("#^(-*)(.*)#ius", $configItem, $matches)) {

                    $level = strlen($matches[1]);

                    if ($prevLevel < $level) {
                        $nestedKeys[] = $prevLevelName;

                    } elseif ($prevLevel > $level) {

                        for ($i = 1; $i <= $prevLevel - $level; $i++) {
                            array_pop($nestedKeys);
                        }
                    }

                    if (count($nestedKeys) > $maxLevel) {
                        $maxLevel = count($nestedKeys);
                    }

                    $listTitle = $configNames[$level];

                    $resultItems = $this->_addToNestedList($matches[2], $resultItems, $nestedKeys, $listTitle);

                    $prevLevelName = $matches[2];
                    $prevLevel     = $level;
                }
            }
        }

        $result = array(
            'items'    => $resultItems,
            'names'    => $configNames,
            'maxLevel' => $maxLevel,
        );

        return $result;
    }

    /**
     * Add item to nested list
     * @param string  $item
     * @param array   $resultArr
     * @param array   $nestedKeys
     * @param string  $listTitle
     * @return array
     */
    protected function _addToNestedList($item, array $resultArr, array $nestedKeys, $listTitle)
    {
        $tmpArr = & $resultArr;

        if (!empty($nestedKeys)) {
            foreach ($nestedKeys as $nestedKey) {
                $tmpArr = & $tmpArr[$nestedKey];
            }
        }

        $tmpArr[$item] = array();

        return $resultArr;
    }

}
