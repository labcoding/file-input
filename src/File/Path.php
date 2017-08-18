<?php

namespace LabCoding\FileInput\File;

use PathByIdGenerator\PathByIdGenerator;

class Path implements PathInterface
{

    /**
     * @var string
     */
    protected $root = './';

    /**
     * @var string
     */
    protected $pathPrefix;

    /**
     * @var PathByIdGenerator
     */
    protected $pathGenerator;

    /**
     * Path constructor.
     *
     * @param PathByIdGenerator $pathGenerator
     * @param array $options
     */
    public function __construct(PathByIdGenerator $pathGenerator, $options = [])
    {
        $this->pathGenerator = $pathGenerator;
        $this->setOptions($options);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        foreach($options as $key => $value) {
            if(property_exists($this, $key) == false) {
                throw new \RuntimeException(sprintf("property '%s' not found in " . __CLASS__, $key));
            }
            $this->{$key} = $value;
        }
    }

    /**
     * Return relative path
     *
     * @param array $params
     * @return string
     */
    public function getPath($params = [])
    {
        $path = $this->pathPrefix . DIRECTORY_SEPARATOR;
        if(isset($params['id']) && !empty($params['id'])) {
            $path .=  $this->pathGenerator->generatePath($params['id']) . DIRECTORY_SEPARATOR;
        }

        return self::fixPath($path);
    }

    /**
     * Return absolute path
     *
     * @param array $params
     * @return string
     */
    public function getAbsolutePath($params = [])
    {
        return self::fixPath($this->root . DIRECTORY_SEPARATOR . $this->getPath($params) . DIRECTORY_SEPARATOR);
    }

    /**
     * Resolve paths with ../, //, etc...
     *
     * @param string $path
     *
     * @return string
     */
    public static function fixPath($path)
    {
        if (func_num_args() > 1) {
            return self::fixPath(implode(DIRECTORY_SEPARATOR, func_get_args()));
        }

        $replace = ['#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#'];

        do {
            $path = preg_replace($replace, DIRECTORY_SEPARATOR, $path, -1, $n);
        } while ($n > 0);

        return $path;
    }
}
