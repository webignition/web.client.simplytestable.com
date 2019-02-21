<?php

namespace App\Services;

class CompletedTestUrlMatcher implements UrlMatcherInterface
{
    /**
     * @var string[]
     */
    private $positiveMatchPatterns = [];

    /**
     * @var string[]
     */
    private $negativeMatchPatterns = [];

    public function __construct(
        CachedDataProvider $positiveMatchUrlPathProvider,
        CachedDataProvider $negativeMatchUrlPathProvider
    ) {
        $this->positiveMatchPatterns = $positiveMatchUrlPathProvider->getData();
        $this->negativeMatchPatterns = $negativeMatchUrlPathProvider->getData();
    }

    public function match(string $urlPath): bool
    {
        foreach ($this->negativeMatchPatterns as $pattern) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/i';

            if (preg_match($regex, $urlPath)) {
                return false;
            }
        }

        foreach ($this->positiveMatchPatterns as $pattern) {
            $regex = '/'. str_replace('/', '\\/', $pattern) .'/i';

            if (preg_match($regex, $urlPath)) {
                return true;
            }
        }

        return false;
    }
}
