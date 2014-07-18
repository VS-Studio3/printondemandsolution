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


$this->app->jbassets->initJBCompare();

?>

<div class="wrapper-jbcompare jsJBZooCompare <?php echo ($isExists ? ' active ' : 'unactive');?>">

    <div class="active-compare">
        <a href="<?php echo $ajaxUrl;?>" class="jsCompareToggle" title="<?php echo JText::_('JBZOO_COMPARE_REMOVE');?>"><?php echo JText::_('JBZOO_COMPARE_REMOVE');?></a>
        <a href="<?php echo $compareUrl;?>" title="<?php echo JText::_('JBZOO_COMPARE');?>"><?php echo JText::_('JBZOO_COMPARE');?></a>
    </div>

    <div class="unactive-compare">
        <a href="<?php echo $ajaxUrl;?>" class="jsCompareToggle" title="<?php echo JText::_('JBZOO_COMPARE_ADD');?>"><?php echo JText::_('JBZOO_COMPARE_ADD');?></a>
    </div>

</div>
