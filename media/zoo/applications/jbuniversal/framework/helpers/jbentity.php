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


class JBEntityHelper extends AppHelper
{
    /**
     * Elements cache
     * @var array
     */
    protected $_elements = array();

    /**
     * Item types cache
     * @var array
     */
    protected $_types = array();

    /**
     * Appplications cache
     * @var array
     */
    protected $_applications = array();

    /**
     * Class constructor
     * @param $app App
     */
    public function __construct($app)
    {
        $this->app   = $app;
        $this->_name = strtolower(basename(get_class($this), 'Helper'));

        $this->app->loader->register('Type', 'classes:type.php');
        $this->app->loader->register('FilterElement', 'classes:/filter/element.php');
    }

    /**
     * Get element by id
     * @param string      $elementId
     * @param string|null $type
     * @param string|null $applicationId
     * @return mixed
     * @throws Exception
     */
    public function getElement($elementId, $type = null, $applicationId = null)
    {
        if (!isset($this->_elements[$elementId])) {
            $zooType                     = $this->getType($type, $applicationId);
            $this->_elements[$elementId] = $zooType->getElement($elementId);
        }

        if (isset($this->_elements[$elementId])) {
            return $this->_elements[$elementId];
        }

        throw new Exception('Unknow element.' . print_r(func_num_args(), true));
    }

    /**
     * Get type
     * @param string $type
     * @param int    $applicationId
     * @return Type
     */
    public function getType($type, $applicationId)
    {
        if (!isset($this->_types[$type])) {
            $application         = $this->getApplication($applicationId);
            $this->_types[$type] = new Type($type, $application);
        }

        return $this->_types[$type];
    }

    /**
     * Get application by ID
     * @param int $applicationId
     * @return Application
     */
    public function getApplication($applicationId)
    {
        $applicationId = (int)$applicationId;

        if (!isset($this->_applications[$applicationId])) {
            $this->_applications[$applicationId] = $this->app->table->application->get($applicationId);
        }

        return $this->_applications[$applicationId];
    }

    /**
     * Get element model
     * @param string  $elementId
     * @param string  $type
     * @param int     $applicationId
     * @param boolean $isRange
     * @return JBModelElement
     */
    public function getElementModel($elementId, $type, $applicationId, $isRange = false)
    {
        $elementType = '';

        $element = $this->getElement($elementId, $type, $applicationId);
        if ($element) {
            $elementType = strtolower(basename(get_class($element)));
            $elementType = str_replace('element', '', $elementType);
        }

        $modelName = 'JBModelElement' . $elementType;
        if ($isRange) {
            //$modelName = 'JBModelElementRange' . $elementType;
        }

        if (class_exists($modelName)) {
            return new $modelName($element, $applicationId);

        } elseif ($isRange && class_exists('JBModelElementRange')) {
            return new JBModelElementRange($element, $applicationId);

        } elseif (!$isRange && class_exists('JBModelElement')) {
            return new JBModelElement($element, $applicationId);

        } else {
            $this->app->error->raiseError(500, 'Not found model ' . $modelName);
        }

        return null;
    }

    /**
     * Get all itemtypes data
     * @return array
     */
    public function getItemTypesData()
    {
        static $result;

        if (!isset($result)) {

            $typesPath = $this->app->path->path('jbtypes:');
            $files     = JFolder::files($typesPath, '.config');

            $result = array();
            foreach ($files as $file) {
                $fileContent = JFile::read($typesPath . '/' . $file);
                $typeData    = json_decode($fileContent, true);
                $result      = array_merge($result, $typeData['elements']);
            }
        }

        return $result;
    }

    /**
     * Get element type by it ID
     * @param string $elementId
     * @return null|string
     */
    public function getTypeByElementId($elementId)
    {
        $elements = $this->getItemTypesData();
        if (isset($elements[$elementId])) {
            return $elements[$elementId]['type'];
        }

        return null;
    }
}
