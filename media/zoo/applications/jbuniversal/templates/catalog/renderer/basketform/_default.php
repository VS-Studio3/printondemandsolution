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

$view = $this->getView();

?>
<h2><?php echo JText::_('JBZOO_CART_CREATE_ORDER_TITLE');?></h2>

<form id="item-submission" class="submission jbbasket-submission" action="<?php echo JRoute::_('index.php'); ?>" method="post" name="submissionForm" accept-charset="utf-8" enctype="multipart/form-data">

    <?php
    echo $view->renderer->render($view->layout_path, array(
        'item'       => $view->item,
        'submission' => $view->submission
    ));
    ?>

    <p class="required-info"><?php echo JText::_('JBZOO_CART_REQUIRED_INFO'); ?></p>

    <p class="submit-button">
        <input type="submit" name="submit" class="add-to-cart" style="display:inline-block;" value="<?php echo JText::_('JBZOO_CART_CREATE_ORDER');?>">
    </p>

    <input type="hidden" name="option" value="com_zoo" />
    <input type="hidden" name="controller" value="basket" />
    <input type="hidden" name="task" value="createOrder" />
    <input type="hidden" name="type" value="<?php echo $view->submissionType;?>" />
    <input type="hidden" name="app_id" value="<?php echo $view->appId;?>" />
    <input type="hidden" name="Itemid" value="<?php echo $view->Itemid;?>" />
    <?php echo $this->app->html->_('form.token'); ?>
</form>
