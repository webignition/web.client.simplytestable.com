<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Services\ApplicationConfigurationService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class ApplicationConfigurationServiceTest extends AbstractBaseTestCase
{
    /**
     * @var ApplicationConfigurationService
     */
    private $applicationConfigurationService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->applicationConfigurationService = $this->container->get(ApplicationConfigurationService::class);
    }

    public function testGetCacheDir()
    {
        $this->assertEquals(
            $this->container->get('kernel')->getCacheDir(),
            $this->applicationConfigurationService->getCacheDir()
        );
    }

    public function testGetRootDir()
    {
        $this->assertEquals(
            $this->container->get('kernel')->getRootDir(),
            $this->applicationConfigurationService->getRootDir()
        );
    }

    public function testGetWebDir()
    {
        $this->assertEquals(
            realpath($this->container->get('kernel')->getRootDir() . '/../web'),
            $this->applicationConfigurationService->getWebDir()
        );
    }
}
