<?php

namespace LabCoding\FileInput\Service;

use LabCoding\FileInput\Config;
use LabCoding\FileInput\File\NameInterface;
use LabCoding\FileInput\File\PathInterface;
use Zend\EventManager\EventManager;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileInputServiceAbstractFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return substr($requestedName, -strlen('FileInputService')) == 'FileInputService';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {

        $params = explode('\\', $requestedName);

        $requestedName = $params[0];

        $config = $serviceLocator->get('Config');

        $config = new Config($config['file_input']);

        $name = $config->getName($requestedName);
        if($serviceLocator->has($name)) {
            $name = $serviceLocator->get($name);

            if (!$name instanceof NameInterface) {
                throw new \RuntimeException(sprintf('"%s" not instance \LabCoding\FileInput\File\NameInterface', $name));
            }
        }

        /** @var PathInterface $path */
        $path = $serviceLocator->get('FileInput\File\Path');
        $path->setOptions($config->getPathOptions($requestedName));

        $uploader = $serviceLocator->get($config->getUploader($requestedName));
        if(!$uploader instanceof \LabCoding\FileInput\Storage\UploaderFileInterface) {
            throw new \RuntimeException(sprintf('"%s" not instance \LabCoding\FileInput\Storage\UploaderFileInterface', $uploader));
        }

        $eventManager = new EventManager();
        $eventManager->setIdentifiers('LabCoding\FileInput');

        return new FileInputService($config->getProperty($requestedName), $uploader, $path, $name, $eventManager);
    }
}
