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

$this->app->jbdebug->mark('layout::myorders::start');

$view = $this->getView();

if (count($vars['objects'])) : ?>
<table class="jbbasket-table" border="1" cellpadding="3" cellspacing="3">
    <thead>
    <tr>
        <th>#</th>
        <th><?php echo JText::_('JBZOO_MYORDERS_NAME');?></th>
        <th><?php echo JText::_('JBZOO_MYORDERS_PRICE');?></th>
        <th><?php echo JText::_('JBZOO_MYORDERS_STATUS');?></th>
    </tr>
    </thead>
    <tbody>
        <?php
        foreach ($vars['objects'] as $id => $item) {

            $layout = $this->app->jblayout->_getItemLayout($item, 'teaser');

            echo $view->renderer->render($layout, array(
                'view' => $view,
                'item' => $item
            ));
        }
        ?>
    </tbody>
</table>
<?php else: ?>
<p style="color:#a00;"><?php echo JText::_('JBZOO_MYORDERS_EMPTY');?></p>
<?php endif;

$this->app->jbdebug->mark('layout::myorders::finish');
