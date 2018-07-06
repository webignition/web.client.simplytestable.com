<?php

namespace SimplyTestable\WebClientBundle\Services\Pdp;

use Pdp\Cache;
use Psr\SimpleCache\CacheInterface;

class CacheFactory
{
    const PSL_CACHE_PATH_SUFFIX = '/psl-data';

    /**
     * @return CacheInterface
     */
    public function create()
    {
        return new Cache(sys_get_temp_dir() . self::PSL_CACHE_PATH_SUFFIX);
    }
}
