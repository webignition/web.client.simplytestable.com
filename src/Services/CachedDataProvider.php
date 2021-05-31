<?php

namespace App\Services;

use Symfony\Component\Yaml\Yaml;

class CachedDataProvider
{
    /**
     * @var string
     */
    private $resourcePath;

    /**
     * @var mixed
     */
    private $data = [];

    /**
     * @var string
     */
    private $cacheResourcePath = '';

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param string $kernelProjectDirectory
     * @param string $cacheDir
     * @param string|null $resource
     * @param string|null $cacheName
     */
    public function __construct(
        $kernelProjectDirectory,
        $cacheDir,
        $resource = null,
        $cacheName = null
    ) {
        if (null === $resource) {
            $this->data = [];
            $this->resourcePath = null;
        } else {
            $this->resourcePath = $kernelProjectDirectory . '/config/resources' . $resource;
            $this->cacheResourcePath = $cacheDir . '/' . $cacheName . '.php.meta';
            $this->cacheDir = $cacheDir;

            $serialisedDataPath = $this->cacheResourcePath;

            if (@file_exists($serialisedDataPath)) {
                $this->data = unserialize(file_get_contents($serialisedDataPath));
            } else {
                $this->data = $this->createData();
            }
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string[]
     */
    public function createData()
    {
        if (null === $this->resourcePath) {
            return [];
        }

        return Yaml::parseFile($this->resourcePath);
    }

    /**
     * @return string
     */
    public function getCacheResourcePath()
    {
        return $this->cacheResourcePath;
    }
}
