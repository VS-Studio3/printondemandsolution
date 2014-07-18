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

?>

<p style="height:36px;">
    <!-- noindex -->
    <a href="http://robokassa.ru/" target="_blank" rel="nofollow"><img src="media/zoo/applications/jbuniversal/assets/img/payments/interkassa.png"></a>
    <!-- /noindex -->
</p>

<form name="payment" action="https://www.interkassa.com/lib/payment.php" method="post"
      enctype="application/x-www-form-urlencoded">

    <input type="hidden" name="ik_shop_id" value="<?php echo $data->get('shopid');?>">
    <input type="hidden" name="ik_payment_amount" value="<?php echo $data->get('summ');?>">
    <input type="hidden" name="ik_payment_id" value="<?php echo $data->get('orderId');?>">
    <input type="hidden" name="ik_payment_desc"
           value="Order #<?php echo $data->get('orderId');?> form <?php echo JUri::getInstance()->getHost();?>">

    <input type="submit" style="display:inline-block;" class="add-to-cart" value="<?php echo JText::_('JBZOO_PAYMENT_BUTTON');?>" />
</form>