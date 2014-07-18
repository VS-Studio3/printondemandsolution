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

$zoo = App::getInstance('zoo');
$zoo->jbassets->fancybox();
$zoo->jbassets->initJBPrice();


$htmlCurrency = array();
foreach ($currencyList as $currency) {

    $activeClass = '';
    if ($currency == $activeCur) {
        $activeClass = ' active';
    }

    $htmlCurrency[] = '<span class="price-currency jsPriceCurrency ' . $activeClass . '" currency="' . $currency . '">' . $currency . '</span>';
}

$classes = array(
    'jbprice-wrapper',
    'jbprice-wrapper-' . $count,
    ($isInCart ? 'in-cart' : 'not-in-cart'),
    'jsPrice',
    'jsPrice-' . $this->identifier . '-' . $this->getItem()->id
);

?>
<div class="<?php echo implode(' ', $classes);?>">

    <?php if ((int)$params->get('show_sku', 1)) : ?>
    <div class="item-sku">
        <strong><?php echo JText::_('JBZOO_CART_ITEM_SKU');?></strong>:
        <?php echo $this->_getSku();?>
    </div>
    <?php endif;?>

    <?php
    if (!$nopaidOrder) {
        if (count($htmlCurrency) > 1) {
            echo '<div class="currency-list">' . implode("\n", $htmlCurrency) . '</div>';
            echo $values;
        } else {
            echo $values;
        }
    }
    ?>

    <?php if ((int)$params->get('show_basket', 1) && $this->_isInStock()) : ?>
    <!-- noindex -->
    <a rel="nofollow" href="<?php echo $modalUrl;?>" class="jsAddToCart add-to-cart"
       title="<?php echo JText::_('JBZOO_CART_ADD');?>"><?php echo JText::_('JBZOO_CART_ADD');?></a>
    <a rel="nofollow" href="<?php echo $removeFromCartUrl;?>" class="jsRemoveFromCart remove-from-cart"
       title="<?php echo JText::_('JBZOO_CART_REMOVE');?>"><?php echo JText::_('JBZOO_CART_REMOVE');?></a>
    <!-- /noindex -->
    <?php endif;?>

    <?php if (!$this->_isInStock()): ?>
    <p class="not-in-stock"><?php echo JText::_('JBZOO_CART_NOT_IN_STOCK'); ?><p>
    <?php endif;?>

</div>
