<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Services\ResourceLocator;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

class ResourceLocatorTest extends AbstractBaseTestCase
{
    const BUNDLE_CONFIG_PATH = '@SimplyTestableWebClientBundle/Resources/config';

    /**
     * @var ResourceLocator
     */
    private $resourceLocator;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->resourceLocator = $this->container->get(ResourceLocator::class);
    }

    public function testLocateSuccess()
    {
        $path = $this->resourceLocator->locate(self::BUNDLE_CONFIG_PATH . '/routing.yml');

        $this->assertInternalType('string', $path);
    }

    public function testLocateFailure()
    {
        $this->setExpectedException(\InvalidArgumentException::class);

        $this->resourceLocator->locate(self::BUNDLE_CONFIG_PATH . '/foo.yml');
    }
}
