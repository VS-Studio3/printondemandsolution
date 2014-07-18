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

class ElementJBFavorite extends Element
{
    /**
     * Element constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCallback('ajaxToggleFavorite');
    }

    /**
     * Checks if the repeatables element's value is set.
     * @param array $params
     * @return bool
     */
    public function hasValue($params = array())
    {
        return true;
    }

    /**
     * Get repeatable elements search data
     * @return null
     */
    public function getSearchData()
    {
        return null;
    }

    /**
     * Override. Renders the element
     * @param array $params
     * @return string
     */
    public function render($params = array())
    {
        $params = $this->app->data->create($params);

        $isExists = $this->app->jbfavorite->isExists($this->getItem());

        $item        = $this->getItem();
        $ajaxUrl     = $this->app->jbrouter->element($this->identifier, $item->id, 'ajaxToggleFavorite');
        $favoriteUrl = $this->app->jbrouter->favorite($this->config->get('menuitem'), $item->getApplication()->id);

        // render layout
        if ($layout = $this->getLayout()) {
            return $this->renderLayout($layout, array(
                'ajaxUrl'     => $ajaxUrl,
                'favoriteUrl' => $favoriteUrl,
                'isExists'    => $isExists,
            ));
        }

        return null;
    }

    /**
     * Renders the edit form field
     * @return string
     */
    public function edit()
    {
        return null;
    }

    /**
     * Ajax callback for toggle favotite flag
     */
    public function ajaxToggleFavorite()
    {
        $result = array(
            'status' => false,
        );

        $result['status'] = $this->app->jbfavorite->toggleState($this->getItem());

        $this->app->jbajax->send($result, true);
    }
}
