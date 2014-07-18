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

$this->app->jbdebug->mark('layout::comments::start');

// css classes
$css[] = $vars['params']->get('max_depth', 5) > 1 ? 'nested' : null;
$css[] = $vars['params']->get('registered_users_only') && $vars['active_author']->isGuest() ? 'no-response' : null;

// add js and css
$this->app->document->addScript('libraries:jquery/plugins/cookie/jquery.cookie.js');
$this->app->document->addScript('assets:js/comment.js');

?>
<div id="comments" class="comments <?php echo implode(" ", $css); ?>">

    <h3 class="comments-meta">
        <span class="comments-count"><?php echo JText::_('Comments') . ' (' . (count($vars['comments']) - 1) . ')'; ?></span>
    </h3>

    <?php

    if (!$this->app->jbcache->start(array(count($vars['comments']), $vars['params']), 'comments')) {
        ?>
        <ul class="level1">
            <?php
            foreach ($vars['comments'][0]->getChildren() as $comment) {
                echo $this->app->jblayout->render('comment', $vars['item'], array(
                        'level'   => 1,
                        'comment' => $comment,
                        'author'  => $comment->getAuthor(),
                        'params'  => $vars['params'],
                    )
                );
            }
            ?>
        </ul>
        <?php
        $this->app->jbcache->stop();
    }

    if ($vars['item']->isCommentsEnabled()) {
        echo $this->app->jblayout->render('respond', $vars['item'], array(
                'active_author' => $vars['active_author'],
                'params'        => $vars['params'],
                'item'          => $vars['item'],
                'captcha'       => $vars['captcha'],
            )
        );
    }

    ?>

</div>

<script type="text/javascript">
    jQuery(function ($) {
        $('#comments').Comment({
            cookiePrefix  :'<?php echo CommentHelper::COOKIE_PREFIX; ?>',
            cookieLifetime:'<?php echo CommentHelper::COOKIE_LIFETIME; ?>',
            msgCancel     :'<?php echo JText::_('Cancel'); ?>'
        });
    });
</script>
<div class="clear clr"></div>

<?php
$this->app->jbdebug->mark('layout::comments::finish');