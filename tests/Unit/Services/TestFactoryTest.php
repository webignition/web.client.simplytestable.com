<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;
use App\Services\RemoteTestCompletionPercentCalculator;
use App\Services\TestFactory;

class TestFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestFactory
     */
    private $testFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->testFactory = new TestFactory(new RemoteTestCompletionPercentCalculator());
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(TestEntity $entity, RemoteTest $remoteTest, TestModel $expectedTest)
    {
        $test = $this->testFactory->create($entity, $remoteTest);

        $this->assertInstanceOf(TestModel::class, $test);
        $this->assertEquals($expectedTest, $test);
    }

    public function createDataProvider(): array
    {
        return [
            'no tasks' => [
                'entity' => TestEntity::create(1),
                'remoteTest' => new RemoteTest([
                    'id' => 1,
                    'website' => 'http://example.com/',
                    'user' => 'user@example.com',
                    'state' => TestEntity::STATE_COMPLETED,
                    'type' => TestEntity::TYPE_FULL_SITE,
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ],
                    'url_count' => 1,
                    'task_count' => 0,
                    'errored_task_count' => 0,
                    'cancelled_task_count' => 0,
                ]),
                'expectedTest' => new TestModel(
                    TestEntity::create(1),
                    'http://example.com/',
                    'user@example.com',
                    TestEntity::STATE_COMPLETED,
                    TestEntity::TYPE_FULL_SITE,
                    [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    1,
                    0,
                    0,
                    0,
                    0,
                    0,
                    '',
                    [],
                    0,
                    [
                        'in_progress' => 0,
                        'queued' => 0,
                        'completed' => 0,
                        'cancelled' => 0,
                        'failed' => 0,
                        'skipped' => 0,
                    ],
                    [],
                    null
                ),
            ],
            'has tasks' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(
                        $this->createTaskOutput(2, 3)
                    )
                ]),
                'remoteTest' => new RemoteTest([
                    'id' => 1,
                    'website' => 'http://example.com/',
                    'user' => 'user@example.com',
                    'state' => TestEntity::STATE_COMPLETED,
                    'type' => TestEntity::TYPE_FULL_SITE,
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ],
                    'url_count' => 1,
                    'task_count' => 2,
                    'errored_task_count' => 0,
                    'cancelled_task_count' => 0,
                ]),
                'expectedTest' => new TestModel(
                    $this->createTestEntity(1, [
                        $this->createTask(
                            $this->createTaskOutput(2, 3)
                        )
                    ]),
                    'http://example.com/',
                    'user@example.com',
                    TestEntity::STATE_COMPLETED,
                    TestEntity::TYPE_FULL_SITE,
                    [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    1,
                    2,
                    3,
                    2,
                    0,
                    0,
                    '',
                    [],
                    0,
                    [
                        'in_progress' => 0,
                        'queued' => 0,
                        'completed' => 0,
                        'cancelled' => 0,
                        'failed' => 0,
                        'skipped' => 0,
                    ],
                    [],
                    null
                ),
            ],
        ];
    }

    /**
     * @dataProvider createWithMissingRemoteTestPropertiesDataProvider
     */
    public function testCreateWithMissingRemoteTestProperties(array $remoteTestData, TestModel $expectedTest)
    {
        $test = $this->testFactory->create(
            TestEntity::create(1),
            new RemoteTest($remoteTestData)
        );

        $this->assertEquals($expectedTest, $test);
    }

    public function createWithMissingRemoteTestPropertiesDataProvider(): array
    {
        return [
            'id only' => [
                'remoteTestData' => [
                    'id' => 1,
                ],
                'expectedTest' => new TestModel(
                    TestEntity::create(1),
                    '',
                    '',
                    '',
                    '',
                    [],
                    0,
                    0,
                    0,
                    0,
                    0,
                    0,
                    '',
                    [],
                    0,
                    [
                        'in_progress' => 0,
                        'queued' => 0,
                        'completed' => 0,
                        'cancelled' => 0,
                        'failed' => 0,
                        'skipped' => 0,
                    ],
                    [],
                    null
                ),
            ],
        ];
    }

    private function createTestEntity(int $testId, array $tasks = []): TestEntity
    {
        $test = TestEntity::create($testId);

        foreach ($tasks as $task) {
            $test->addTask($task);
        }

        return $test;
    }

    private function createTask(?Output $output = null): Task
    {
        $task = new Task();

        if ($output) {
            $task->setOutput($output);
        }

        return $task;
    }

    private function createTaskOutput(int $errorCount, int $warningCount): Output
    {
        $output = new Output();
        $output->setErrorCount($errorCount);
        $output->setWarningCount($warningCount);

        return $output;
    }
}
