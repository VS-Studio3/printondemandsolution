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

$this->app->jbassets->initJBFavorite();

?>

<div class="wrapper-jbfavorite jsJBZooFavorite <?php echo ($isExists ? ' active ' : 'unactive');?>">

    <div class="active-favorite">
        <a href="<?php echo $ajaxUrl;?>" class="jsFavoriteToggle" title="<?php echo JText::_('JBZOO_FAVORITE_REMOVE');?>"><?php echo JText::_('JBZOO_FAVORITE_REMOVE');?></a>
        <a href="<?php echo $favoriteUrl;?>" title="<?php echo JText::_('JBZOO_FAVORITE');?>"><?php echo JText::_('JBZOO_FAVORITE');?></a>
    </div>

    <div class="unactive-favorite">
        <a href="<?php echo $ajaxUrl;?>" class="jsFavoriteToggle" title="<?php echo JText::_('JBZOO_FAVORITE_ADD');?>"><?php echo JText::_('JBZOO_FAVORITE_ADD');?></a>
    </div>

</div>
