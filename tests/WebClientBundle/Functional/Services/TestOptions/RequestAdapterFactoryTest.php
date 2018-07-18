<?php

namespace Tests\WebClientBundle\Functional\Services\TestOptions;

use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapter;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapterFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
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
        $user = new User('user@example.com');

        $taskTypeService = self::$container->get(TaskTypeService::class);
        $taskTypeService->setUser($user);
        $taskTypeService->setUserIsAuthenticated();

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
