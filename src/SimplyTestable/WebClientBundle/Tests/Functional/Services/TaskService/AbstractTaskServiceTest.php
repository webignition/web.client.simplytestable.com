<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractTaskServiceTest extends BaseSimplyTestableTestCase
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

        $this->taskService = $this->container->get(
            'simplytestable.services.taskservice'
        );

        $user = new User('user@example.com');
        $this->taskService->setUser($user);
    }
}
