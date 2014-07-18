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


if (empty($items)) {
    echo '<p>' . JText::_('JBZOO_CART_ITEMS_NOT_FOUND') . '</p>';

} else {
    ?>
<div>
    <table class="jbbasket-table jsJBZooBasket" border="1" cellpadding="3" cellspacing="3">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo JText::_('JBZOO_CART_ITEM_SKU');?></th>
            <th><?php echo JText::_('JBZOO_CART_ITEM_NAME');?></th>
            <th><?php echo JText::_('JBZOO_CART_ITEM_PRICE');?></th>
            <th><?php echo JText::_('JBZOO_CART_ITEM_QUANTITY');?></th>
            <th><?php echo JText::_('JBZOO_CART_ITEM_SUBTOTAL');?></th>
        </tr>
        </thead>
        <tbody>
            <?php
            $i        = 0;
            $summa    = 0;
            $count    = 0;
            $currency = '';
            foreach ($items as $item) {
                $basketInfo = $basketItems[$item->id];
                $count += $basketInfo['quantity'];

                $currency = $basketInfo['currency'];

                $subtotal = $basketInfo['quantity'] * $basketInfo['price'];
                $summa += $subtotal;

                $itemLink = $this->app->jbrouter->adminItem($item);
                if ($this->app->jbenv->isSite()) {
                    $itemLink = $this->app->route->item($item);
                }

                echo '<tr class="row-' . $item->id . '" itemId="' . $item->id . '">';
                echo '<td>' . ++$i . '</td>';
                echo '<td>' . $basketInfo['sku'] . '</td>';
                echo '<td><a href="' . $itemLink . '" title="' . $item->name . '">' . $item->name . '</a><br/>
                    <span class="price-description">' . $basketInfo['priceDesc'] . '</span></td>';

                echo '<td class="jsPricevalue" price="' . $basketInfo['price'] . '">'
                    . $this->app->jbmoney->toFormat($basketInfo['price'], $currency) . '</td>';

                echo '<td>' . $basketInfo['quantity'] . '</td>';
                echo '<td class="jsSubtotal">' . $this->app->jbmoney->toFormat($subtotal, $currency) . '</td>';
                echo "</tr>\n";
            }
            ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td><strong><?php echo JText::_('JBZOO_CART_TOTAL');?>:</strong></td>
            <td class="jsTotalCount"><?php echo $count;?></td>
            <td class="jsTotalPrice"><?php echo $this->app->jbmoney->toFormat($summa, $currency);?></td>
        </tr>
        </tfoot>
    </table>
    <div class="payment-system">

        <?php if (($params && $params->get('payment-info', true)) || !$params) : ?>

            <?php $paymentData = $this->getPaymentData();?>
            <?php if ($paymentData && $summa) : ?>
            <ul>
                <li><strong><?php echo JText::_('JBZOO_CART_REAL_DATE');?>:</strong> <?php echo $paymentData['payment_date'];?></li>
                <li><strong><?php echo JText::_('JBZOO_CART_PAYMENT_NAME');?>:</strong> <?php echo $paymentData['payment_system'];?></li>
                <li>
                    <strong><?php echo JText::_('JBZOO_CART_PAYMENT_STATUS');?>:</strong>
                    <?php echo '<span class="order-status ' . $this->getOrderStatus(false) . '">' . $this->getOrderStatus(true) . '</span>';?>
                </li>
                <?php if ($paymentData['additional_status']) : ?>
                <li><strong><?php echo JText::_('JBZOO_CART_PAYMENT_STATUS_REAL');?>:</strong> <?php echo $paymentData['additional_status'];?></li>
                <?php endif;?>
            </ul>
            <?php else: ?>
            <p><?php echo JText::_('JBZOO_CART_PAYMENT_NODATA');?></p>
            <?php endif;?>
        <?php endif;?>

        <?php if ($this->app->jbenv->isSite() &&
            $this->getOrderStatus() == ElementJBBasketItems::ORDER_STATUS_NOPAID &&
            $params->get('payment-button', true) &&
            $summa > 0
        ) {
            $appId = $this->app->zoo->getApplication()->id;
            $href  = $this->app->jbrouter->basketPayment($params->get('basket-menuitem'), $appId, $this->getItem()->id);
            ?>
            <p><input type="button" style="display:inline-block;" class="jsGoToPayment add-to-cart" value="<?php echo JText::_('JBZOO_PAYMENT_LINKTOFORM');?>" /></p>
            <script type="text/javascript">
                jQuery(function($){
                    $('.jsGoToPayment').click(function(){
                        window.location.href = "<?php echo $href;?>";
                    });
                });
            </script>
        <?php } ?>

    </div>
</div>
<div class="clear"></div>
<?php

}
