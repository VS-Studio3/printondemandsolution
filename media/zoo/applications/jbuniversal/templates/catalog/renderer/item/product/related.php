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


$align = $this->app->jbitem->getMediaAlign($item, $layout);
?>
<?php if ($this->checkPosition('image')) : ?>
    <div class="item-image align-<?php echo $align;?>">
        <?php echo $this->renderPosition('image');?>
    </div>
<?php endif; ?>
<div class="clear clr"></div>

<?php if ($this->checkPosition('title')) : ?>
    <h4 class="item-title"><?php echo $this->renderPosition('title'); ?></h4>
<?php endif; ?>
<div class="clear clr"></div>

<?php echo $this->renderPosition('rating', array('style' => 'block')); ?>

<ul><?php echo $this->renderPosition('properties', array('style' => 'list')); ?></ul>

<p><?php echo $this->renderPosition('links', array('style' => 'pipe')); ?></p>

<div class="clear clr"></div>
