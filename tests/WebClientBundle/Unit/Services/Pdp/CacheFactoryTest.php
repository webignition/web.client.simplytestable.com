<?php

namespace Tests\WebClientBundle\Unit\Services\Pdp;

use Pdp\Cache;
use phpmock\mockery\PHPMockery;
use SimplyTestable\WebClientBundle\Services\Pdp\CacheFactory;

class CacheFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $mockedNamespace = 'SimplyTestable\WebClientBundle\Services\Pdp';
        $mockedSysGetTempDirReturnValue = '/foo';

        PHPMockery::mock(
            $mockedNamespace,
            'sys_get_temp_dir'
        )->andReturn($mockedSysGetTempDirReturnValue);

        $cacheFactory = new CacheFactory();

        $cache = $cacheFactory->create();

        $this->assertInstanceOf(Cache::class, $cache);

        $reflectionClass = new \ReflectionClass($cache);
        $cachePathProperty = $reflectionClass->getProperty('cache_path');
        $cachePathProperty->setAccessible(true);

        $this->assertSame(
            $mockedSysGetTempDirReturnValue . CacheFactory::PSL_CACHE_PATH_SUFFIX,
            $cachePathProperty->getValue($cache)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
