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

$zoo = App::getInstance('zoo');
$zoo->jbassets->initJBPrice();

echo '<span class="jbprice-row jbprice-row-' . $params['counter'] . '">';
$htmlValues = array();

foreach ($values as $currency => $value) {

    $activeClass = '';
    if ($currency == $activeCur) {
        $activeClass = ' active';
    }

    $htmlValues[] = '<span class="price-value jsPriceValue price-currency-' . $currency . $activeClass . '">' . $value . '</span>';
}

if ($description) {
    echo '<span class="description">' . $description . '</span> ';
}
echo implode("\n", $htmlValues) . "\n";
echo '</span>';
