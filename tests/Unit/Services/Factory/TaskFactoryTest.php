<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Factory;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test;
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

        $test = Test::create(1);
        $task = $taskFactory->create($test);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals($test, $task->getTest());
    }

    /**
     * @dataProvider hydrateDataProvider
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
        $this->assertEquals($expectedTaskData['type'], $task->getType());
        $this->assertEquals($expectedTaskData['hasOutput'], $task->hasOutput());
    }

    public function hydrateDataProvider(): array
    {
        return [
            'no output' => [
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
