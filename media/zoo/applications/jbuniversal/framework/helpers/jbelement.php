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


class JBElementHelper extends AppHelper
{

    /**
     * Parse text by lines
     * @param string $text
     * @return array
     */
    public function parseLines($text)
    {
        $text  = JString::trim($text);
        $lines = explode("\n", $text);

        $result = array();
        if (!empty($lines)) {

            foreach ($lines as $line) {

                $line = JString::trim($line);
                if (!empty($line)) {
                    $result[] = $line;
                }

            }
        }

        return $result;
    }

}
