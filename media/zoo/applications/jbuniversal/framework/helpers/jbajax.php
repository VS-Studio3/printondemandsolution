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


class JBAjaxHelper extends AppHelper
{

    /**
     * Send response in JSON-format
     * @param array $data
     * @param bool  $result
     */
    public function send(array $data = array(), $result = true)
    {

        $data['result'] = $result;

        JResponse::allowCache(false);
        JResponse::setHeader('Last-Modified', gmdate('D, d M Y H:i:s', time()) . ' GMT', true);
        JResponse::setHeader('Content-Type', 'application/json; charset=utf-8', true);
        JResponse::sendHeaders();

        jexit(json_encode($data));
    }

}
