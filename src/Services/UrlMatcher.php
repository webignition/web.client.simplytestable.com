<?php

namespace App\Services;

class UrlMatcher
{
    /**
     * @var string[]
     */
    private $patterns = [];

    /**
     * @param UrlPathProvider $urlPathProvider
     */
    public function __construct(UrlPathProvider $urlPathProvider)
    {
        $this->patterns = $urlPathProvider->getPatterns();
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
