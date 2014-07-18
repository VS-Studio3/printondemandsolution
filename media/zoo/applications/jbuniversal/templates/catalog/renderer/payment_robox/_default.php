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
$data = $vars['object'];

$isDebug = (int)$data->get('debug', 0);

$action = 'https://merchant.roboxchange.com/Index.aspx';
if ($isDebug) {
    $action = 'http://test.robokassa.ru/Index.aspx';
}

?>
<p style="height:36px;"><!-- noindex --><a href="http://robokassa.ru/" target="_blank" rel="nofollow"><img src="media/zoo/applications/jbuniversal/assets/img/payments/robokassa.png"></a><!-- /noindex --></p>
<form action="<?php echo $action;?>" method=POST>
    <input type="hidden" name="MrchLogin" value="<?php echo $data->get('login');?>">
    <input type="hidden" name="OutSum" value="<?php echo $data->get('summ');?>">
    <input type="hidden" name="InvId" value="<?php echo $data->get('orderId');?>">
    <input type="hidden" name="Desc" value="Order #<?php echo $data->get('orderId');?> form <?php echo JUri::getInstance()->getHost();?>">
    <input type="hidden" name="SignatureValue" value="<?php echo $data->get('hash');?>">

    <input type="submit" style="display:inline-block;" class="add-to-cart" value="<?php echo JText::_('JBZOO_PAYMENT_BUTTON');?>" />
</form>

<?php if ($isDebug) : ?>
    <strong style="color:red;"><?php echo JText::_('JBZOO_ROBOX_DEBUG_MODE');?></strong>
<?php endif;?>