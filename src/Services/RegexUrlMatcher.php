<?php

namespace App\Services;

class RegexUrlMatcher implements UrlMatcherInterface
{
    /**
     * @var string[]
     */
    private $patterns = [];

    public function __construct(CachedDataProvider $urlPathProvider)
    {
        $this->patterns = $urlPathProvider->getData();
    }

    public function match(string $urlPath): bool
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
