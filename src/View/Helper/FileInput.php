<?php

namespace LabCoding\FileInput\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorInterface;
use LabCoding\FileInput\Config;
use LabCoding\FileInput\File\PathInterface;

class FileInput extends AbstractHelper
{

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var PathInterface
     */
    private $filePath;

    /**
     * @var array
     */
    private $pathParams = [];

    /**
     * @var string
     */
    private $fileInputName;

    /**
     * FileInput constructor.
     * @param ServiceLocatorInterface $serviceLocator
     * @param Config $config
     * @param PathInterface $filePath
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, Config $config, PathInterface $filePath)
    {
        $this->serviceLocator = $serviceLocator;
        $this->config = $config;
        $this->filePath = $filePath;
    }

    public function __invoke($fileInputName)
    {
        $this->fileInputName = $fileInputName;

        $this->filePath->setOptions($this->config->getPathOptions($fileInputName));

        return $this;
    }

    /**
     * @param array $pathParams
     * @return $this
     */
    public function setPathParams(array $pathParams)
    {
        $this->pathParams = $pathParams;

        return $this;
    }

    /**
     * @param $fileName
     * @param array $params
     * @return string
     */
    public function getFile($fileName, $params = [])
    {
        $params = (!empty($params)) ? $params : $this->pathParams;

        if(empty($fileName)) {
            $preview = $this->config->getPreview($this->fileInputName);
            if(is_callable($preview)) {
                return call_user_func($preview, $this->serviceLocator, $fileName, $params);
            }
            return $preview;
        }

        $path = $this->filePath->getPath($params);

        return $path . $fileName;
    }

    /**
     * @param $fileName
     * @param array $params
     * @return mixed
     */
    public function getFileUrl($fileName, $params = [])
    {
        $params = (!empty($params)) ? $params : $this->pathParams;

        $file = $this->getFile($fileName, $params);

        return $this->serviceLocator->get('ViewHelperManager')->get('serverUrl')->__invoke($file);
    }
}