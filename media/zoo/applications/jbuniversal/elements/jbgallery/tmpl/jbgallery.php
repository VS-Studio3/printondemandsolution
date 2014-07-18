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


$this->app->jbassets->fancybox();

?>

<div id="<?php echo $galleryId; ?>">

    <?php foreach ($thumbs as $thumb) { ?>

    <a href="<?php echo $thumb['img']; ?>"
       rel="<?php echo $rel; ?>"
       class="jbgallery"><img src="<?php echo $thumb['thumb']; ?>"
                              alt="<?php echo $thumb['name']; ?>"
                              width="<?php echo $thumb['thumb_width']; ?>"
                              height="<?php echo $thumb['thumb_height']; ?>"
            /></a>

    <?php } ?>

    <div class="clear clr"></div>
</div>

<script type="text/javascript">
    jQuery(function ($) {
        $('#<?php echo $galleryId; ?> .jbgallery').fancybox({
            helpers:{
                "title"  : { type : "outside" },
                "buttons": { position:"top" },
                "thumbs" : { width :80, height:80 }
            }
        });
    });
</script>
