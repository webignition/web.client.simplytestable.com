<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Factory;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

class FactoryTest extends BaseSimplyTestableTestCase
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->container->get('simplytestable.services.testoptions.adapter.factory');
    }

    public function testCreate()
    {
        $user = new User('user@example.com');

        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $taskTypeService->setUser($user);
        $taskTypeService->setUserIsAuthenticated();

        $adapter = $this->factory->create();
        $adapter->setRequestData(new ParameterBag([]));

        $this->assertInstanceOf(Adapter::class, $adapter);

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
