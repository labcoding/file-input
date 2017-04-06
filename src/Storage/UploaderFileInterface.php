<?php

namespace LabCoding\FileInput\Storage;

use LabCoding\FileInput\File\FileInterface;

interface UploaderFileInterface
{

    /**
     * Moves an uploaded file to a new location
     *
     * @param FileInterface $file
     * @return mixed
     */
    public function upload(FileInterface $file);

    /**
     * Rename a file
     *
     * @param string $oldName absolute path to file
     * @param string $newName absolute path to file
     * @return mixed
     */
    public function rename($oldName, $newName);

    /**
     * Deletes a file
     *
     * @param string $file absolute path to file
     * @return mixed
     */
    public function remove($file);
}