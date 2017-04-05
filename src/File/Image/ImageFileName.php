<?php

namespace LabCoding\FileInput\File\Image;

use LabCoding\FileInput\File\NameInterface;

class ImageFileName implements NameInterface
{

    public function __invoke(\SplFileInfo $file = null, $params = [])
    {

        list($width, $height) = getimagesize($file->getPathname());

        $md5 = md5_file($file->getPathname());

        return $md5 . '_' . $width . 'x' . $height;
    }
}