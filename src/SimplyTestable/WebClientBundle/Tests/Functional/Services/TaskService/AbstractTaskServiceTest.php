<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Tests\Functional\Services\AbstractCoreApplicationServiceTest;

abstract class AbstractTaskServiceTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->taskService = $this->container->get('simplytestable.services.taskservice');

        $user = new User('user@example.com');

        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser($user);
    }
}
