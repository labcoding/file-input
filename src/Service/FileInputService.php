<?php

namespace LabCoding\FileInput\Service;

use LabCoding\FileInput\Storage\UploaderFileInterface;
use LabCoding\FileInput\File\PathInterface;
use LabCoding\FileInput\File\NameInterface;
use Zend\EventManager\EventManagerInterface;
use LabCoding\FileInput\File\File;
use LabCoding\FileInput\Event\UploadEvent;

class FileInputService
{

    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var UploaderFileInterface
     */
    protected $uploader;

    /**
     * @var PathInterface
     */
    protected $filePath;

    /**
     * @var NameInterface
     */
    protected $fileName;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * @var File
     */
    protected $file;

    public function __construct(
        $propertyName,
        UploaderFileInterface $uploader,
        PathInterface $filePath,
        NameInterface $fileName = null,
        EventManagerInterface $eventManager = null
    )
    {
        $this->propertyName = $propertyName;
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->uploader = $uploader;
        $this->eventManager = $eventManager;
    }

    /**
     * @param mixed $params
     * @param array $changes
     * @return array|null
     */
    public function handle($params, $changes)
    {

        if (isset($changes[$this->propertyName]) == false) {
            throw new \InvalidArgumentException("Cannot find uploaded file identified by key: \"$this->propertyName\"");
        }

        $file = $changes[$this->propertyName];

        if (isset($file['name']) == false || isset($file['tmp_name']) == false) {
            throw new \InvalidArgumentException("Invalid file array");
        }

        if(empty($file['name'])) {
            return null;
        }

        $this->file = new File($file['tmp_name']);
        $this->file->setOriginalName($file['name']);

        if (!empty($this->fileName)) {
            // set new name to file
            $this->file->setName($this->fileName->__invoke($this->file));
        }

        $absolutePath = $this->filePath->getAbsolutePath($params);
        $this->uploader->setDirectory($absolutePath);

        $newFile = $this->uploader->upload($this->file);
        $this->file->setFile($newFile);

        $event = new UploadEvent(
            UploadEvent::EVENT_FILE_UPLOADED,
            $this,
            [
                'file' => $this->file,
                'data' => $changes,
            ]
        );

        $this->eventManager->trigger($event);

        if ($event->getResult() != null) {
            $this->file = $event->getResult();
        }

        $path = $this->filePath->getPath($params);

        return [
            'name' => $this->file->getNameWithExtension(),
            'file' => $path . $this->file->getNameWithExtension(),
            'type' => $this->file->getMimeType(),
        ];

    }
}