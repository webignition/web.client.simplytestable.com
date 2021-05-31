<?php

namespace App\Cache;

use App\Services\CachedDataProvider;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class UrlMatcherCacheWarmer extends CacheWarmer implements CacheWarmerInterface
{
    /**
     * @var CachedDataProvider
     */
    private $urlPathProviders;

    /**
     * @param CachedDataProvider[] $urlPathProviders
     */
    public function __construct($urlPathProviders)
    {
        $this->urlPathProviders = $urlPathProviders;
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
        foreach ($this->urlPathProviders as $urlPathProvider) {
            $this->writeCacheFile(
                $urlPathProvider->getCacheResourcePath(),
                serialize($urlPathProvider->createData())
            );
        }
    }
}
