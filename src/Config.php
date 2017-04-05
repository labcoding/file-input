<?php

namespace LabCoding\FileInput;

use LabCoding\FileInput\File\NameInterface;

class Config
{
    /**
     * @var array
     */
    protected $fileInput = [];

    public function __construct(array $fileInput)
    {
        $this->fileInput = $fileInput;
    }

    /**
     * @param string $key
     * @return string mixed
     */
    public function getProperty($key)
    {
        if (!isset($this->fileInput[$key]['property'])) {
            throw new ConfigException(sprintf("file_input[property] not configured for %s", $key));
        }

        return $this->fileInput[$key]['property'];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getUploader($key)
    {
        if (!isset($this->fileInput[$key]['uploader'])) {
            throw new ConfigException(sprintf("file_input[uploader] not configured for %s", $key));
        }
//        if (!$this->fileInput[$key]['uploader'] instanceof UploaderFileInterface) {
//            throw new ConfigException(sprintf('file_input[uploader] for %s not instance \LabCoding\FileInput\Storage\UploaderFileInterface', $key));
//        }

        return $this->fileInput[$key]['uploader'];
    }

//    /**
//     * @param string $key
//     * @return string
//     */
//    public function getPathPrefix($key)
//    {
//        if (!isset($this->fileInput[$key]['pathPrefix'])) {
//            return '';
//        }
//
//        return $this->fileInput[$key]['pathPrefix'];
//    }
//
//    /**
//     * @param string $key
//     * @return string mixed
//     */
//    public function getPath($key)
//    {
//        if (!isset($this->fileInput[$key]['path'])) {
//            throw new ConfigException(sprintf("file_input[path] not configured for %s", $key));
//        }
//
//        return $this->fileInput[$key]['path'];
//    }

    /**
     * @param string $key
     * @return string mixed
     */
    public function getPathOptions($key)
    {
        if (!isset($this->fileInput[$key]['path'])) {
            throw new ConfigException(sprintf("file_input[path] not configured for %s", $key));
        }

        return $this->fileInput[$key]['path'];
    }

    /**
     * @param $key
     * @return null|NameInterface
     */
    public function getName($key)
    {
        if (!isset($this->fileInput[$key]['name'])) {
            return null;
        }

        return $this->fileInput[$key]['name'];
    }

    /**
     * @param string $key
     * @return string
     */
    public function getPreview($key)
    {
        if (!isset($this->fileInput[$key]['preview'])) {
            return '';
        }

        return $this->fileInput[$key]['preview'];
    }
}
