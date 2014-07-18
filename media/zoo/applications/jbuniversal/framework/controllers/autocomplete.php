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


require_once dirname(__FILE__) . '/base.php';

class AutocompleteJBUniversalController extends BaseJBUniversalController
{
    const MAX_LENGTH = 30;

    /**
     * @throws Exception
     */
    public function index()
    {
        $this->_init('filter');

        $this->app->jbdebug->mark('autocomplete::start');
        if (!$this->app->jbcache->start(null, 'autocomplete')) {

            $type    = $this->_jbreq->get('type');
            $query   = $this->_jbreq->get('value');
            $appId   = $this->_jbreq->get('app_id');
            $element = $this->_jbreq->get('name');

            if ($element && preg_match('#^e\[(.*?)\]#i', $element, $elementName)) {
                $elementName = $elementName[1];

                $autocomleteDb = JBModelAutocomplete::model();

                if ($elementName == '_itemname') {
                    $rows = $autocomleteDb->name($query, $type, $appId);

                } elseif ($elementName == '_itemtag') {
                    $rows = $autocomleteDb->tag($query, $type, $appId);

                } elseif ($elementName == '_itemauthor') {
                    $rows = $autocomleteDb->author($query, $type, $appId);

                } else {
                    $rows = $autocomleteDb->field($query, $elementName, $type, $appId);

                }

                $data = array();
                if (!empty($rows)) {

                    foreach ($rows as $row) {

                        if (JString::strlen($row->value) > self::MAX_LENGTH) {
                            $value = $this->app->jbstring->smartSubstr($row->value, $query);
                        } else {
                            $value = $row->value;
                        }

                        $data[] = array(
                            'id'    => $value,
                            'label' => $value,
                            'value' => JString::trim($value, '.'),
                        );
                    }
                }

                echo json_encode($data);

            } else {
                throw new Exception('Unkown element name');
            }

            $this->app->jbcache->stop();
        }

        $this->app->jbdebug->mark('autocomplete::end');
        jexit();
    }

}
