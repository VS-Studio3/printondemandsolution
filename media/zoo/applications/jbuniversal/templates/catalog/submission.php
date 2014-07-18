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

// add page title
$page_title = sprintf(($this->item->id ? JText::_('Edit %s') : JText::_('Add %s')), '');
$this->app->document->setTitle($page_title);

$css_class = $this->application->getGroup() . '-' . $this->template->name;

$class = array('zoo', 'jbzoo', 'yoo-zoo', $css_class, $css_class . '-' . $this->submission->alias);

?>

<div id="yoo-zoo" class="<?php echo implode(' ', $class); ?>">

    <div class="submission">

        <h1 class="headline"><?php echo $page_title;?></h1>

        <?php echo $this->partial('submission'); ?>

    </div>

</div>
