<?php

namespace App\Tests\Unit\Services\Factory;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Services\Factory\TaskFactory;
use App\Services\Factory\TaskOutputFactory;
use App\Tests\Factory\MockFactory;

class TaskFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        /* @var TaskOutputFactory $taskOutputFactory */
        $taskOutputFactory = \Mockery::mock(TaskOutputFactory::class);

        $taskFactory = new TaskFactory($taskOutputFactory);

        $test = new Test();
        $task = $taskFactory->create($test);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($test, $task->getTest());
    }

    /**
     * @dataProvider hydrateDataProvider
     *
     * @param TaskOutputFactory $taskOutputFactory
     * @param Task $task
     * @param array $taskData
     * @param array $expectedTaskData
     */
    public function testHydrate(
        TaskOutputFactory $taskOutputFactory,
        Task $task,
        array $taskData,
        array $expectedTaskData
    ) {
        $taskFactory = new TaskFactory($taskOutputFactory);

        $taskFactory->hydrate($task, $taskData);

        $this->assertEquals($expectedTaskData['taskId'], $task->getTaskId());
        $this->assertEquals($expectedTaskData['url'], $task->getUrl());
        $this->assertEquals($expectedTaskData['state'], $task->getState());
        $this->assertEquals($expectedTaskData['worker'], $task->getWorker());
        $this->assertEquals($expectedTaskData['type'], $task->getType());
        $this->assertEquals($expectedTaskData['hasOutput'], $task->hasOutput());

        $timePeriod = $task->getTimePeriod();

        $this->assertEquals($expectedTaskData['timePeriod']['startDateTime'], $timePeriod->getStartDateTime());
        $this->assertEquals($expectedTaskData['timePeriod']['endDateTime'], $timePeriod->getEndDateTime());
    }

    /**
     * @return array
     */
    public function hydrateDataProvider()
    {
        return [
            'no output, no time period' => [
                'taskOutputFactory' => MockFactory::createTaskOutputFactory(),
                'task' => new Task(),
                'taskData' => [
                    'id' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                ],
                'expectedTaskData' => [
                    'taskId' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'timePeriod' => [
                        'startDateTime' => null,
                        'endDateTime' => null,
                    ],
                    'hasOutput' => false,
                ],
            ],
            'no output, has start date time' => [
                'taskOutputFactory' => MockFactory::createTaskOutputFactory(),
                'task' => new Task(),
                'taskData' => [
                    'id' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'time_period' => [
                        'start_date_time' => '2018-02-14 10:00',
                    ],
                ],
                'expectedTaskData' => [
                    'taskId' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'timePeriod' => [
                        'startDateTime' => new \DateTime('2018-02-14 10:00'),
                        'endDateTime' => null,
                    ],
                    'hasOutput' => false,
                ],
            ],
            'no output, has end date time' => [
                'taskOutputFactory' => MockFactory::createTaskOutputFactory(),
                'task' => new Task(),
                'taskData' => [
                    'id' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'time_period' => [
                        'end_date_time' => '2018-02-14 10:00',
                    ],
                ],
                'expectedTaskData' => [
                    'taskId' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'timePeriod' => [
                        'startDateTime' => null,
                        'endDateTime' => new \DateTime('2018-02-14 10:00'),
                    ],
                    'hasOutput' => false,
                ],
            ],
            'task data has output, no existing output' => [
                'taskOutputFactory' => MockFactory::createTaskOutputFactory([
                    'create' => [
                        'withArgs' => [
                            Task::TYPE_HTML_VALIDATION,
                            [
                                'output' => '{"messages":[]}',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ]
                        ],
                        'return' => new Output(),
                    ],
                ]),
                'task' => new Task(),
                'taskData' => [
                    'id' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'output' => [
                        'output' => '{"messages":[]}',
                        'error_count' => 0,
                        'warning_count' => 0,
                    ],
                ],
                'expectedTaskData' => [
                    'taskId' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'worker' => 'foo.worker.simplytestable.com',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'timePeriod' => [
                        'startDateTime' => null,
                        'endDateTime' => null,
                    ],
                    'hasOutput' => true,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
