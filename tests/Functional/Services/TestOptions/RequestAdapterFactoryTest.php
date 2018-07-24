<?php

namespace App\Tests\Functional\Services\TestOptions;

use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapter;
use App\Services\TestOptions\RequestAdapterFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use webignition\SimplyTestableUserModel\User;

class RequestAdapterFactoryTest extends AbstractBaseTestCase
{
    /**
     * @var RequestAdapterFactory
     */
    private $requestAdapterFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->requestAdapterFactory = self::$container->get(RequestAdapterFactory::class);
    }

    public function testCreate()
    {
        $adapter = $this->requestAdapterFactory->create();
        $adapter->setRequestData(new ParameterBag([]));

        $this->assertInstanceOf(RequestAdapter::class, $adapter);

        $testOptions = $adapter->getTestOptions();

        $this->assertEquals(
            [
                'http-authentication',
                'cookies',
            ],
            $testOptions->getFeatures()
        );
    }
}
