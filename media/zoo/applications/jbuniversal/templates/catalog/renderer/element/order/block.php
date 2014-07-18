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

$params = $this->app->data->create($params);

// add tooltip
$tooltip = '';
if ($params->get('show_tooltip') && ($description = $element->config->get('description'))) {
    $tooltip = ' class="hasTip" title="' . $description . '"';
}

// create error
$error = '';
if (@$element->error) {
    $error = '<p class="error-message">' . (string)$element->error . '</p>';
}

// create class attribute
$classes = array(
    0 => 'form-field-row',
    1 => 'element',
    2 => 'element-' . $element->getElementType(),
    3 => ($params->get('first') ? ' first' : ''),
    4 => ($params->get('last') ? ' last' : ''),
    5 => ($params->get('required') ? ' required-field' : ''),
    6 => (@$element->error ? ' error' : ''),
);

$element->loadAssets();

$label = $params->get('altlabel') ? $params->get('altlabel') : $element->config->get('name');
$label = $params->get('required') ? ($label . ' <span class="dot">*</span>') : $label;

?>
<div class="<?php echo implode(' ', $classes);?>">
    <?php
    echo '<label' . $tooltip . '>';
    echo '<div class="field-label"> ' . $label . ' </div>';
    echo '<div class="field-input"> ' . $element->renderSubmission($params) . $error . ' </div>';
    echo '</label>';
    ?>
    <div class="clear"></div>
</div>
