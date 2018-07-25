<?php

namespace App\Services;

class UrlMatcher
{
    /**
     * @var string[]
     */
    private $patterns = [];

    /**
     * @param CachedDataProvider $urlPathProvider
     */
    public function __construct(CachedDataProvider $urlPathProvider)
    {
        $this->patterns = $urlPathProvider->getData();
    }

    /**
     * @param string $urlPath
     *
     * @return bool
     */
    public function match($urlPath)
    {
        return null !== $this->getMatchPattern($urlPath);
    }

    /**
     * @param string $urlPath
     *
     * @return null|string
     */
    public function getMatchPattern($urlPath)
    {
        foreach ($this->patterns as $pattern) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/';

            if (preg_match($regex, $urlPath)) {
                return $pattern;
            }
        }

        return null;
    }
}
