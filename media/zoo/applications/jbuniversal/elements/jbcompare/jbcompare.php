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


class ElementJBCompare extends Element
{
    /**
     * Element constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->registerCallback('ajaxToggleCompare');
    }

    /**
     * @param array $params
     * @return bool
     */
    public function hasValue($params = array())
    {
        return true;
    }

    /**
     * @param array $params
     * @return null|string
     */
    public function render($params = array())
    {
        $isExists = $this->app->jbcompare->isExists($this->getItem());

        $item       = $this->getItem();
        $ajaxUrl    = $this->app->jbrouter->element($this->identifier, $item->id, 'ajaxToggleCompare');
        $compareUrl = $this->app->jbrouter->compare($this->config->get('menuitem'), 'v', $item->type, $item->getApplication()->id);

        if ($layout = $this->getLayout('jbcompare.php')) {
            return self::renderLayout($layout, array(
                'ajaxUrl'    => $ajaxUrl,
                'compareUrl' => $compareUrl,
                'isExists'   => $isExists,
            ));
        }

        return null;
    }

    /**
     * Ajax toggle compare action
     * @param array $params
     */
    public function ajaxToggleCompare($params = array())
    {
        $result = array(
            'status' => false,
        );
        
        $itemIds = $this->app->jbcompare->getItemsByType($this->getItem()->type);
        if (!isset($itemIds[$this->getItem()->id])) {
            if (count($itemIds) >= $this->config->get('limit', 4)) {
                $result['status'] = false;
                $result['message'] = JText::_('JBZOO_COMPARE_LIMIT_ERROR');
            }
        }
        
        if (!isset($result['message'])) {
            if ($this->app->jbcompare->toggleState($this->getItem())) {
                $result['status'] = true;
            }
        }

        $this->app->jbajax->send($result, true);
    }

    /**
     * No render HTML
     * @return null
     */
    public function edit()
    {
        return null;
    }

}