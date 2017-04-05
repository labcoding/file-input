<?php

namespace LabCoding\FileInput\File;

interface PathInterface
{

    /**
     * @param array $params
     * @return mixed
     */
    public function getPath($params = []);

    /**
     * @param array $params
     * @return mixed
     */
    public function getAbsolutePath($params = []);
}