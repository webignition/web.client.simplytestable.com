<?php

namespace Tests\AppBundle\Functional\Services\TaskService;

use App\Services\TaskService;
use Tests\AppBundle\Functional\Services\AbstractCoreApplicationServiceTest;

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
