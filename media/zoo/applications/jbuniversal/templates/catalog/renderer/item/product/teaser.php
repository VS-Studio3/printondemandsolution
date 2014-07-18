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

<div class="product-props">
    <?php if ($this->checkPosition('title')) : ?>
    <h4 class="item-title"><?php echo $this->renderPosition('title'); ?></h4>
    <?php endif; ?>

    <p><i><?php echo $this->renderPosition('subtitle', array('style' => 'block')); ?></i></p>

    <?php if ($this->checkPosition('price')) : ?>
    <p><?php echo $this->renderPosition('price'); ?></p>
    <?php endif; ?>

    <?php if ($this->checkPosition('properties')) : ?>
    <ul>
        <?php echo $this->renderPosition('properties', array('style' => 'list')); ?>
    </ul>
    <?php endif; ?>

    <?php echo $this->renderPosition('rating', array('style' => 'block')); ?>
</div>
<div class="clear clr"></div>
<?php echo $this->renderPosition('links', array('style' => 'pipe')); ?>
<div class="clear clr"></div>
