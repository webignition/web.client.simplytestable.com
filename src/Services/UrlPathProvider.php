<?php

namespace App\Services;

use Symfony\Component\Yaml\Yaml;

class UrlPathProvider
{
    /**
     * @var string
     */
    private $patternsResourcePath;

    /**
     * @var string[]
     */
    private $patterns = [];

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
            $this->patterns = [];
            $this->patternsResourcePath = null;
        } else {
            $this->patternsResourcePath = $kernelProjectDirectory . '/config/resources' . $resource;
            $this->cacheResourcePath = $cacheDir . '/' . $cacheName . '.php.meta';
            $this->cacheDir = $cacheDir;

            $serialisedPatternsPath = $this->cacheResourcePath;

            if (@file_exists($serialisedPatternsPath)) {
                $this->patterns = unserialize(file_get_contents($serialisedPatternsPath));
            } else {
                $this->patterns = $this->createPatterns();
            }
        }
    }

    /**
     * @return string[]
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @return string[]
     */
    public function createPatterns()
    {
        if (null === $this->patternsResourcePath) {
            return [];
        }

        return Yaml::parseFile($this->patternsResourcePath);
    }

    /**
     * @return string
     */
    public function getCacheResourcePath()
    {
        return $this->cacheResourcePath;
    }
}
