<?php

namespace LabCoding\FileInput\File;

interface FileInterface
{
    public function getName();
    public function getOriginalName();
    public function getNameWithExtension();
    public function getExtension();
    public function getRealPath();
    public function getDimensions();
}