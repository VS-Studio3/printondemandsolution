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


class JBFilterElementHidden extends JBFilterElement {

    /**
     * Render HTML code for element
     * @return string|null
     */
    public function html()
    {
        $html = array();

        if (is_array($this->_value)) {

            unset($this->_attrs['multiple']);
            unset($this->_attrs['size']);

            foreach ($this->_value as $key => $value) {
                $html[] = $this->app->jbhtml->hidden(
                    $this->_getName($key),
                    $value,
                    $this->_attrs,
                    $this->_getId($key)
                );
            }

        } else {
            $html[] = $this->app->jbhtml->hidden(
                $this->_getName(),
                $this->_value,
                $this->_attrs,
                $this->_getId()
            );
        }

        return implode("\n", $html);
    }

}
