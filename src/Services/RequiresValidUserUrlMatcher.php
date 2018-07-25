<?php

namespace App\Services;

use Symfony\Component\Yaml\Yaml;

class RequiresValidUserUrlMatcher
{
    const PATTERNS_RESOURCE = '/requires-valid-user-url-paths.yml';

    /**
     * @var string
     */
    private $patternsResourcePath;

    /**
     * @var string[]
     */
    private $patterns = [];

    private $cacheResourcePath = '';

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param string $kernelProjectDirectory
     * @param string $cacheDir
     */
    public function __construct(
        $kernelProjectDirectory,
        $cacheDir
    ) {
        $this->patternsResourcePath = $kernelProjectDirectory . '/config/resources' . self::PATTERNS_RESOURCE;
        $this->cacheResourcePath = $cacheDir . '/' . str_replace('\\', '', self::class) . '.php.meta';
        $this->cacheDir = $cacheDir;

        $serialisedPatternsPath = $this->cacheResourcePath;

        if (@file_exists($serialisedPatternsPath)) {
            $this->patterns = unserialize(file_get_contents($serialisedPatternsPath));
        } else {
            $this->patterns = $this->createPatterns();
        }
    }

    /**
     * @return string[]
     */
    public function createPatterns()
    {
        return Yaml::parseFile($this->patternsResourcePath);
    }

    /**
     * @return string
     */
    public function getCacheResourcePath()
    {
        return $this->cacheResourcePath;
    }

    /**
     * @param string $urlPath
     *
     * @return bool
     */
    public function match($urlPath)
    {
        foreach ($this->patterns as $pattern) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/';

            if (preg_match($regex, $urlPath)) {
                return true;
            }
        }

        return false;
    }
}
