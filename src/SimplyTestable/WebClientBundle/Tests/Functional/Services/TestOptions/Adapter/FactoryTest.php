<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Factory;
use SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Request\Adapter;
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

        $this->factory = $this->container->get('simplytestable.services.testoptions.adpater.factory');
    }

    public function testCreate()
    {
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');

        $user = new User('user@example.com');
        $taskTypeService->setUser($user);

        $adapter = $this->factory->create();

        $this->assertInstanceOf(Adapter::class, $adapter);
    }
}
