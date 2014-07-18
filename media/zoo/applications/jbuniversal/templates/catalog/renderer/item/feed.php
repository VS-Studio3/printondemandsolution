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



$desc = JString::trim(strip_tags($this->renderPosition('description')));

$descArr = explode(' ', $desc);
$descArr_tmp = array();

foreach ($descArr as $word) {
    $word = JString::trim($word);

    if ($word) {
        $descArr_tmp[] = $word;
    }

}

if (count($descArr_tmp) > 30) {
    $descArr_tmp = array_slice($descArr_tmp, 0, 30);
    echo implode(' ', $descArr_tmp) . ' ...';

} else {
    echo implode(' ', $descArr_tmp);
}
