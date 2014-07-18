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

$this->app->jbdebug->mark('layout::tag::start');

$newItems = array();

foreach ($items as $item) {
    $newItems[$item->type][] = $item;
}

?>


<?php foreach ($newItems as $key=> $newsItem) {
    $items = $newsItem;
    ?>

    <div class="module-header"><?php echo JText::_('SEARCH IN ' . $key); ?>:</div>
    <?php echo $this->partial('items', compact('items', 'is_subcategory')); ?>

<?php } ?>

<?php
$this->app->jbdebug->mark('layout::tag::finish');
