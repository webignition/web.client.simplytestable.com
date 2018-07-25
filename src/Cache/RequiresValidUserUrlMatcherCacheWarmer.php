<?php

namespace App\Cache;

use App\Services\CoreApplicationRouter;
use App\Services\RequiresValidUserUrlMatcher;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class RequiresValidUserUrlMatcherCacheWarmer extends CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var RequiresValidUserUrlMatcher
     */
    private $requiresValidUserUrlMatcher;

    /**
     * @param RequiresValidUserUrlMatcher $requiresValidUserUrlMatcher
     */
    public function __construct(RequiresValidUserUrlMatcher $requiresValidUserUrlMatcher)
    {
        $this->requiresValidUserUrlMatcher = $requiresValidUserUrlMatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        $this->writeCacheFile(
            $this->requiresValidUserUrlMatcher->getCacheResourcePath(),
            serialize($this->requiresValidUserUrlMatcher->createPatterns())
        );
    }
}
