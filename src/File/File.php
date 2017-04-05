<?php

namespace LabCoding\FileInput\File;

/**
 * File
 *
 * This class provides the implementation for an uploaded file. It exposes
 * common attributes for the uploaded file (e.g. name, extension, media type)
 * and allows you to attach validations to the file that must pass for the
 * upload to succeed.
 *
 * @author  Josh Lockhart <info@joshlockhart.com>
 * @since   1.0.0
 * @package Upload
 */
class File extends \SplFileInfo implements FileInterface
{

    /**
     * Original file name provided by client (for internal use only)
     * @var string
     */
    protected $originalName;

    /**
     * File name (without extension)
     * @var string
     */
    protected $name;

    /**
     * File extension (without leading dot)
     * @var string
     */
    protected $extension;

    /**
     * File mimetype (e.g. "image/png")
     * @var string
     */
    protected $mimeType;

    public function __construct($file)
    {
//        if (!isset($file['name']) || !isset($file['tmp_name'])) {
//            throw new \InvalidArgumentException("Invalid file array");
//        }
//        $this->originalName = $file['name'];

        parent::__construct($file);
    }

    public function setFile($file)
    {
        parent::__construct($file);

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @param string $originalName
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        if (!isset($this->name)) {
            $this->name = pathinfo($this->originalName, PATHINFO_FILENAME);
        }

        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get file name with extension
     * @return string
     */
    public function getNameWithExtension()
    {
        return sprintf('%s.%s', $this->getName(), $this->getExtension());
    }

    /**
     * Get file extension (without leading dot)
     * @return string
     */
    public function getExtension()
    {
        if (!isset($this->extension)) {
            $this->extension = strtolower(pathinfo($this->originalName, PATHINFO_EXTENSION));
        }

        return $this->extension;
    }

    /**
     * Get mimetype
     * @return string
     */
    public function getMimeType()
    {
        if (!isset($this->mimeType)) {
            $fInfo = new \finfo(FILEINFO_MIME);
            $mimeType = $fInfo->file($this->getPathname());
            $mimeTypeParts = preg_split('/\s*[;,]\s*/', $mimeType);
            $this->mimeType = strtolower($mimeTypeParts[0]);
            unset($fInfo);
        }

        return $this->mimeType;
    }

    /**
     * Get md5
     * @return string
     */
    public function getMd5()
    {
        return md5_file($this->getPathname());
    }

    /**
     * Get image dimensions
     * @return array formatted array of dimensions
     */
    public function getDimensions()
    {
        list($width, $height) = getimagesize($this->getPathname());

        return array(
            'width' => $width,
            'height' => $height
        );
    }
}
