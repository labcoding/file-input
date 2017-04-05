<?php

namespace LabCoding\FileInput\File;

interface NameInterface
{

    /**
     * @param \SplFileInfo $file
     * @param array $params
     * @return mixed
     */
    public function __invoke(\SplFileInfo $file, $params = []);
}