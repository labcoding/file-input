<?php

namespace LabCoding\FileInput\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LabCoding\FileInput\Config;
use LabCoding\FileInput\File\PathInterface;

class FileInputFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();

        $config = $serviceLocator->get('Config');

        $config = new Config($config['file_input']);
        /** @var PathInterface $path */
        $path = $serviceLocator->get('FileInput\File\Path');

        return new FileInput($serviceLocator, $config, $path);
    }
}