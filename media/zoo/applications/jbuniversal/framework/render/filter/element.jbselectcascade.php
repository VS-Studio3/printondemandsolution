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


class JBFilterElementJBSelectcascade extends JBFilterElement
{
    /**
     * Render HTML
     * @return string|null
     */
    function html()
    {
        $selectInfo = $this->app->jbselectcascade->getItemList(
            $this->_config->get('select_names', ''),
            $this->_config->get('items', '')
        );

        return $this->app->jbhtml->selectCascade(
            $selectInfo,
            $this->_getName(),
            $this->_value,
            $this->_attrs,
            $this->_getId()
        );
    }
}
