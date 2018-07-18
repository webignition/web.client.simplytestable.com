<?php

namespace Tests\WebClientBundle\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Services\TaskService;
use Tests\WebClientBundle\Functional\Services\AbstractCoreApplicationServiceTest;

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

        $this->taskService = self::$container->get(TaskService::class);
    }
}
