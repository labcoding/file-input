<?php

namespace LabCoding\FileInput\Storage;

use LabCoding\FileInput\File\FileInterface;

class Uploader implements UploaderFileInterface
{

    /**
     * Upload directory
     * @var string
     */
    protected $directory;

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = rtrim($directory, '/') . DIRECTORY_SEPARATOR;
    }

    /**
     * Moves an uploaded file to a new location
     *
     * @param FileInterface $file
     * @return mixed
     */
    public function upload(FileInterface $file)
    {
        if (!is_dir($this->directory)) {
            $this->mkdir($this->directory);
        }
        if (!is_writable($this->directory)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" is not writable', $this->directory));
        }

        $newFile = $this->directory . $file->getNameWithExtension();

        $result = $this->moveUploadedFile($file->getRealPath(), $newFile);
        if($result == true) {
            return $newFile;
        }

        return null;
    }

    /**
     * Rename a file
     *
     * @param string $oldName absolute path to file
     * @param string $newName absolute path to file
     * @return mixed
     */
    public function rename($oldName, $newName)
    {
        $dirName = pathinfo($newName, PATHINFO_DIRNAME);
        if(!is_dir($dirName)) {
            $this->mkdir($dirName);
        }
        @rename($oldName, $newName);
    }

    /**
     * Deletes a file
     *
     * @param string $file absolute path to file
     * @return mixed
     */
    public function remove($file)
    {
        @unlink($file);
    }

    /**
     * Move uploaded file
     *
     * This method allows us to stub this method in unit tests to avoid
     * hard dependency on the `move_uploaded_file` function.
     *
     * @param  string $source      The source file
     * @param  string $destination The destination file
     * @return bool
     */
    protected function moveUploadedFile($source, $destination)
    {
        return move_uploaded_file($source, $destination);
    }

    /**
     * Creates a directory recursively.
     *
     * @param string|array $directory The directory path
     * @param int                       $mode The directory mode
     *
     * @throws \RuntimeException On any directory creation failure
     */
    protected function mkdir($directory, $mode = 0777)
    {

        $dirs = new \ArrayObject(is_array($directory) ? $directory : array($directory));

        foreach ($dirs as $dir) {
            if (is_dir($dir)) {
                continue;
            }

            if (true !== @mkdir($dir, $mode, true)) {
                $error = error_get_last();
                if (!is_dir($dir)) {
                    // The directory was not created by a concurrent process. Let's throw an exception with a developer friendly error message if we have one
                    if ($error) {
                        throw new \RuntimeException(sprintf('Failed to create directory"%s": %s.', $dir, $error['message']));
                    }
                    throw new \RuntimeException(sprintf('Failed to create directory "%s"', $dir));
                }
            }
        }
    }
}