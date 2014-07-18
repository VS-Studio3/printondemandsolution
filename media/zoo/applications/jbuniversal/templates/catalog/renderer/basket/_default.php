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
$this->app->jbassets->basket();
$this->app->jbassets->initJBPrice();
?>
<table class="jbbasket-table jsJBZooBasket">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo JText::_('JBZOO_CART_ITEM_SKU');?></th>
        <th></th>
        <th><?php echo JText::_('JBZOO_CART_ITEM_NAME');?></th>
        <th style="min-width: 70px;"><?php echo JText::_('JBZOO_CART_ITEM_PRICE');?></th>
        <th><?php echo JText::_('JBZOO_CART_ITEM_QUANTITY');?></th>
        <th><?php echo JText::_('JBZOO_CART_ITEM_SUBTOTAL');?></th>
        <th></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $i     = 0;
    $summa = 0;
    $count = 0;

    $currencyConvert = $view->appParams->get('global.jbzoo_cart_config.currency');
    $imageElementId  = $view->appParams->get('global.jbzoo_cart_config.element-image');

    foreach ($view->items as $item) {

        $basketInfo = $view->basketItems[$item->id];

        $basketInfo['price'] = $this->app->jbmoney->convert($basketInfo['currency'], $currencyConvert, $basketInfo['price']);

        $count += $basketInfo['quantity'];

        $subtotal = $basketInfo['quantity'] * $basketInfo['price'];
        $summa += $subtotal;

        $image = $this->app->jbitem->renderImageFromItem($item, $imageElementId, true);

        echo '<tr class="row-' . $item->id . '" itemId="' . $item->id . '">';
        echo '<td>' . ++$i . '</td>';
        echo '<td>' . $basketInfo['sku'] . '</td>';
        echo '<td>' . $image . '</td>';

        echo '<td>' . '<a href="' . $this->app->route->item($item) . '" title="' . $item->name . '">' . $item->name . '</a><br/>
                <span class="price-description">' . $basketInfo['priceDesc'] . '</span></td>';

        if ($basketInfo['price']) {
            echo '<td class="jsPricevalue" price="' . $basketInfo['price'] . '">'
                . $this->app->jbformat->number($basketInfo['price'])
                . ' </td>';
        } else {
            echo '<td> - </td>';
        }

        echo '<td><input type="text" class="jsQuantity input-quantity" value="' . $basketInfo['quantity'] . '" /></td>';

        if ($basketInfo['price']) {
            echo '<td class="jsSubtotal">' . $this->app->jbmoney->toFormat($subtotal, $currencyConvert) . '</td>';
        } else {
            echo '<td> - </td>';
        }

        echo '<td><input type="button" class="jbbuttom jsDelete" itemid="' . $item->id . '" value="' . JText::_('JBZOO_CART_DELETE') . '" /></td>';
        echo "</tr>\n";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td><strong><?php echo JText::_('JBZOO_CART_TOTAL');?>:</strong></td>
        <td class="jsTotalCount"><?php echo $count;?></td>
        <td class="jsTotalPrice"><?php echo $this->app->jbmoney->toFormat($summa, $currencyConvert);?></td>
        <td>
            <input type="button" class="jbbuttom jsDeleteAll" value="<?php echo JText::_('JBZOO_CART_REMOVE_ALL');?>"/>
        </td>
    </tr>
    </tfoot>
</table>

<script type="text/javascript">
    jQuery(function ($) {
        $('.jbzoo .jsJBZooBasket').JBZooBasket({
            'clearConfirm':"<?php echo JText::_('JBZOO_CART_CLEAR_CONFIRM');?>",
            'quantityUrl':"<?php echo $this->app->jbrouter->basketQuantity($view->appId);?>",
            'deleteUrl':"<?php echo $this->app->jbrouter->basketDelete($view->appId);?>",
            'clearUrl':"<?php echo $this->app->jbrouter->basketClear($view->appId);?>"
        });
    });
</script>
