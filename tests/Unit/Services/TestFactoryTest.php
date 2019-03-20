<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Services\TestCompletionPercentCalculator;
use App\Services\TestFactory;
use App\Services\TestTaskCountByStateNormaliser;
use App\Services\TestTaskOptionsNormaliser;

class TestFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestFactory
     */
    private $testFactory;

    protected function setUp()
    {
        parent::setUp();

        $this->testFactory = new TestFactory(
            new TestCompletionPercentCalculator(),
            new TestTaskCountByStateNormaliser(),
            new TestTaskOptionsNormaliser()
        );
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(TestEntity $entity, array $testData, TestModel $expectedTest)
    {
        $test = $this->testFactory->create($entity, $testData);

        $this->assertInstanceOf(TestModel::class, $test);
        $this->assertEquals($expectedTest, $test);
    }

    public function createDataProvider(): array
    {
        return [
            'no tasks' => [
                'entity' => TestEntity::create(1),
                'testData' => [
                    'website' => 'http://example.com/',
                    'user' => 'public@simplytestable.com',
                    'state' => TestEntity::STATE_COMPLETED,
                    'type' => TestEntity::TYPE_FULL_SITE,
                    'url_count' => 1,
                    'task_count' => 0,
                    'errored_task_count' => 0,
                    'cancelled_task_count' => 0,
                    'parameters' => '',
                    'amendments' => [],
                    'crawl' => [],
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ],
                    'task_count_by_state' => [],
                    'is_public' => true,
                    'task_type_options' => [],
                    'owners' => [
                        'public@simplytestable.com',
                    ],
                ],
                'expectedTest' => new TestModel(
                    TestEntity::create(1),
                    'http://example.com/',
                    'public@simplytestable.com',
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
                    [],
                    true,
                    [
                        'html-validation' => 1,
                    ],
                    [
                        'public@simplytestable.com',
                    ]
                ),
            ],
            'has tasks' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(
                        $this->createTaskOutput(2, 3)
                    )
                ]),
                'testData' => [
                    'website' => 'http://example.com/',
                    'user' => 'user@example.com',
                    'state' => TestEntity::STATE_COMPLETED,
                    'type' => TestEntity::TYPE_FULL_SITE,
                    'url_count' => 1,
                    'task_count' => 2,
                    'errored_task_count' => 1,
                    'cancelled_task_count' => 2,
                    'parameters' => json_encode(['foo' => 'bar']),
                    'amendments' => [
                        1,
                    ],
                    'crawl' => [
                        2,
                    ],
                    'task_types' => [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    'task_count_by_state' => [],
                    'is_public' => false,
                    'task_type_options' => [
                        'CSS validation' => [
                            'ignore-warnings' => '1',
                        ],
                    ],
                    'owners' => [
                        'user@example.com',
                        'member@example.com',
                    ],
                ],
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
                    1,
                    2,
                    json_encode(['foo' => 'bar']),
                    [
                        1,
                    ],
                    0,
                    [
                        'in_progress' => 0,
                        'queued' => 0,
                        'completed' => 0,
                        'cancelled' => 0,
                        'failed' => 0,
                        'skipped' => 0,
                    ],
                    [
                        2,
                    ],
                    [],
                    false,
                    [
                        'html-validation' => 1,
                        'css-validation-ignore-warnings' => '1',
                    ],
                    [
                        'user@example.com',
                        'member@example.com',
                    ]
                ),
            ],
        ];
    }

    /**
     * @dataProvider createWithMissingRemoteTestPropertiesDataProvider
     */
    public function testCreateWithMissingRemoteTestProperties(
        array $testData,
        TestModel $expectedTest
    ) {
        $test = $this->testFactory->create(
            TestEntity::create(1),
            $testData
        );

        $this->assertEquals($expectedTest, $test);
    }

    public function createWithMissingRemoteTestPropertiesDataProvider(): array
    {
        return [
            'id only' => [
                'testData' => [],
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
                    [],
                    false,
                    [],
                    []
                ),
            ],
        ];
    }

    /**
     * @dataProvider normaliseStateDataProvider
     */
    public function testNormaliseState(string $state, array $crawlData, string $expectedState)
    {
        $test = $this->testFactory->create(
            TestEntity::create(1),
            [
                'state' => $state,
                'crawl' => $crawlData,
                'is_public' => false,
            ]
        );

        $this->assertEquals($expectedState, $test->getState());
    }

    public function normaliseStateDataProvider(): array
    {
        return [
            'empty crawl data' => [
                'state' => TestEntity::STATE_COMPLETED,
                'crawlData' => [],
                'expectedState' => TestEntity::STATE_COMPLETED,
            ],
            'failed no sitemap, empty crawl data' => [
                'state' => TestEntity::STATE_FAILED_NO_SITEMAP,
                'crawlData' => [],
                'expectedState' => TestEntity::STATE_FAILED_NO_SITEMAP,
            ],
            'failed no sitemap, has crawl data' => [
                'state' => TestEntity::STATE_FAILED_NO_SITEMAP,
                'crawlData' => [
                    'discovered_url_count' => 1,
                    'limit' => 2,
                ],
                'expectedState' => TestEntity::STATE_CRAWLING,
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
