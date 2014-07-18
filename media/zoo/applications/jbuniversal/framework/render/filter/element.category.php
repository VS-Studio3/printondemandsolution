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


class JBFilterElementCategory extends JBFilterElement
{
    /**
     * Render HTML
     * @return string
     */
    function html()
    {
        $values = $this->_getValues();

        return $this->app->jbhtml->select(
            $this->_createOptionsList($values),
            $this->_getName(),
            $this->_attrs,
            $this->_value,
            $this->_getId()
        );
    }

    /**
     * Get categories list
     * @return array
     */
    private function _getCategoriesList()
    {
        $applicationId = (int)$this->_params->get('jbzoo_application_id', 0);
        $application   = $this->app->table->application->get($applicationId);
        $modeParam     = $this->_params->get('jbzoo_category_mode', 'tree');

		$allCategories = array();
		if ($application) {
			$allCategories = $application->getCategories(true);
		}

        $result = array();

        if (empty($allCategories)) {
            return $result;
        }

        if ($modeParam == 'parent') {
            // only parents
            foreach ($allCategories as $category) {
                if (!$category->parent) {
                    $result[] = $category;
                }
            }

        } elseif ($modeParam == 'child') {
            // only childs
            foreach ($allCategories as $category) {
                if ($category->parent) {
                    $result[] = $category;
                }
            }

        } elseif ($modeParam == 'tree') {
            // tree view
            $result = $this->app->tree->buildList(0, $this->app->tree->build($allCategories, 'Category'));

        } else {
            $result = $allCategories;
        }

        return $result;
    }

    /**
     * Get categories list values
     * @param null $type
     * @return array
     */
    protected function _getValues($type = null)
    {
        $catList   = $this->_getCategoriesList();
        $catValues = $this->_getDbValues();

        $categoriesList = array();
        foreach ($catList as $category) {

            $found = false;
            foreach ($catValues as $catValue) {

                if ($catValue['value'] == $category->id) {
                    $category->countItems = $catValue['count'];
                    $categoriesList[]     = $category;
                    $found = true;
                    break;
                }

            }

            if (!$found) {
                $category->countItems = 0;
                $categoriesList[]     = $category;
            }

        }

        $modeParam   = $this->_params->get('jbzoo_category_mode', 'tree');
        $filterCount = (int)$this->_params->get('jbzoo_filter_count', 1);

        $options = array();
        foreach ($categoriesList as $category) {

            if ($modeParam == 'tree') {
                $options[] = array(
                    'value' => $category->id,
                    'text'  => '&nbsp;&nbsp;&nbsp;' . $category->treename,
                    'count' => $filterCount ? $category->countItems : null,
                );

            } else {
                $options[] = array(
                    'value' => $category->id,
                    'text'  => $category->name,
                    'count' => $filterCount ? $category->countItems : null,
                );
            }

        }

        return $options;
    }
}
