<?php

namespace LabCoding\FileInput\File;

class FileName implements NameInterface
{

    public function __invoke(\SplFileInfo $file = null, $params = [])
    {
        return md5_file($file->getPathname());
    }
}