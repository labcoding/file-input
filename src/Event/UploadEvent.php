<?php

namespace LabCoding\FileInput\Event;

use LabCoding\FileInput\File\FileInterface;
use Zend\EventManager\Event;
use ArrayAccess;
use Zend\ServiceManager\FactoryInterface;

class UploadEvent extends Event
{
    /**#@+
     * Events triggered by eventmanager
     */
    const EVENT_FILE_UPLOADED= 'file.uploaded';
    /**#@-*/

    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * @var FileInterface|null
     */
    protected $result = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Constructor
     *
     * Accept a target and its parameters.
     *
     * @param  string $name Event name
     * @param  string|object $target
     * @param  array|ArrayAccess $params
     */
    public function __construct($name = null, $target = null, $params = null)
    {
        parent::__construct($name, $target, $params);

        if (!isset($params['file']) && !$params['file'] instanceof FileInterface) {
            throw new \RuntimeException('Bad param "file"');
        }

        $this->file = $params['file'];

        if(isset($params['data'])) {
            $this->data = $params['data'];
        }
    }

    /**
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param FileInterface $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return FileInterface|null
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param FileInterface|null $result
     */
    public function setResult($result)
    {
        if($result instanceof FileInterface) {
            $result->setFile($result->getPath() . DIRECTORY_SEPARATOR . $result->getNameWithExtension());
        }

        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}
