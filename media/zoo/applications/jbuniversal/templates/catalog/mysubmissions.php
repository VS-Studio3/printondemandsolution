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

$appParams = $this->app->zoo->getApplication()->params;

$this->app->jblayout->setView($this);

$this->app->jbwrapper->start();

if ((int)$appParams->get('global.jbzoo_cart_config.enable', 0)) {

    $user = JFactory::getUser();
    if ($user->id) {
        ?>
    <div class="myorders">
        <h1><?php echo JText::_('JBZOO_MYORDERS_TITLE');?></h1>

        <p><?php echo JText::_('JBZOO_MYORDERS_DESCRIPTION');?>:</p>
        <?php echo $this->app->jblayout->render('myorders', $this->items);?>
    </div>

    <?php
    } else {
        $url = $this->app->jbrouter->auth();
        JFactory::getApplication()->redirect($url, JText::_('JBZOO_AUTH_PLEASE'));
    } ?>


<?php } else { ?>

<div class="mysubmissions">
    <h1 class="headline"><?php echo JText::_('My Submissions'); ?></h1>

    <p><?php echo sprintf(JText::_('Hi %s, here you can edit your submissions and add new submission.'), $this->user->name); ?></p>
    <?php echo $this->partial('mysubmissions');?>
</div>

<?php
}

$this->app->jbwrapper->end();
