<?php

namespace App\Tests\Functional\Services\TaskService;

use App\Services\TaskService;
use App\Tests\Functional\Services\AbstractCoreApplicationServiceTest;

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
