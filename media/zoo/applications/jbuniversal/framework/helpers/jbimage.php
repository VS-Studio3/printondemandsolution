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


class JBImageHelper extends AppHelper
{

    /**
     * Get image by params
     * @param $name
     * @param $params
     * @return bool|array
     */
    public function get($name, $params)
    {
        if ($image = $params->get('content.' . $name)) {

            $imageHeight = $params->get('content.' . $name . '_height');
            if (!$imageHeight) {
                $imageHeight = $params->get('config.' . $name . '_height');
            }

            $imageWidth = $params->get('content.' . $name . '_width');
            if (!$imageWidth) {
                $imageWidth = $params->get('config.' . $name . '_width');
            }

            return $this->app->html->_('zoo.image', $image, $imageWidth, $imageHeight);
        }

        return false;
    }

    /**
     * Resize image
     * @param string $imagePath
     * @param int    $width
     * @param int    $height
     * @return object
     */
    public function resize($imagePath, $width = 0, $height = 0)
    {
        if (JFile::exists(JPATH_ROOT . '/' . $imagePath)) {
            $origFilePath = JPATH_ROOT . '/' . $imagePath;
        } else {
            $origFilePath = $imagePath;
        }

        $file = $this->app->zoo->resizeImage($origFilePath, (int)$width, (int)$height);
        $info = $this->_getImageInfo($file);

        return $info;
    }

    /**
     * Get placeholder URL
     * @param     $width
     * @param int $height
     * @return null|string
     */
    public function placeholder($width, $height = 0)
    {
        $serviceUrl = 'http://www.placehold.it/';

        $result = null;
        $width  = (int)$width;
        $height = (int)$height;

        if ($width && $height) {
            $result = $serviceUrl . $width . 'x' . $height;

        } elseif ($width) {
            $result = $serviceUrl . $width;

        } elseif ($height) {
            $result = $serviceUrl . $height;
        }

        return $result;
    }

    /**
     * Get image info
     * @param $path
     * @return object
     */
    private function _getImageInfo($path)
    {
        if (!JFile::exists($path)) {
            return false;
        }

        $info = getimagesize($path);
        return (object)array(
            'width'  => $info[0],
            'height' => $info[1],
            'mime'   => $info['mime'],
            'bits'   => $info['bits'],
            'path'   => $path,
            'url'    => $this->app->path->relative($path),
        );
    }
}
