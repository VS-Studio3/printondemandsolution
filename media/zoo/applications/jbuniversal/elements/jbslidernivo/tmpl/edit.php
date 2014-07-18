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


$id = 'elements[' . $element . ']';
?>

<div id="<?php echo $id; ?>">

    <div class="row">
        <?php echo $this->app->html->_(
        'control.selectdirectory', $directory, false, 'elements[' . $element . '][value]', $value
    ); ?>
    </div>

</div>