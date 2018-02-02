<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

abstract class AbstractTaskServiceTest extends AbstractBaseTestCase
{
    /**
     * @var TaskService
     */
    protected $taskService;

    /**
     * @var HttpHistory
     */
    protected $httpHistory;

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

        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $this->httpHistory = new HttpHistory($httpClientService);
    }
}
