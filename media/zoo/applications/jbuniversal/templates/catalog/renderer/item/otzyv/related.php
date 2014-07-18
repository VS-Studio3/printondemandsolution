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
		
<div class="col-sm-6 inner-left-xs text-left comments">

<?php if ($this->checkPosition('image')) : ?>
<div class="photo_boy">
<?php echo $this->renderPosition('image'); ?>
</div>
<?php endif; ?>


<div>

	<?php if ($this->checkPosition('title')) : ?>
		<span class="name">
			<?php echo $this->renderPosition('title'); ?>
		</span>
	<?php endif; ?>

	
	<?php if ($this->checkPosition('job')) : ?>
		
		<p class="who"><?php echo $this->renderPosition('job'); ?></p>
	<?php endif; ?>
	
	<?php if ($this->checkPosition('site')) : ?>
		
		<?php echo $this->renderPosition('site'); ?>
	<?php endif; ?>
	
	
</div>

<?php if ($this->checkPosition('site')) : ?>
	<div class="for_massages">
		<div class="messages">
		<?php echo $this->renderPosition('site'); ?>
		</div>
	</div>
<?php endif; ?>
	
	


</div>
		
		