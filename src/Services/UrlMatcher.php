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
        foreach ($this->patterns as $pattern) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/i';

            if (preg_match($regex, $urlPath)) {
                return true;
            }
        }

        return false;
    }
}
