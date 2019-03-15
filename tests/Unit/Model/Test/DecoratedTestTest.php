<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model\Test;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;
use App\Model\Test\DecoratedTest;

class DecoratedTestTest extends \PHPUnit\Framework\TestCase
{
    const TEST_ID = 1;
    const WEBSITE = 'http://example.com/';
    const USER = 'user@example.com';
    const STATE = TestEntity::STATE_COMPLETED;
    const TYPE = TestEntity::TYPE_FULL_SITE;
    const TASK_TYPES = [
        Task::TYPE_HTML_VALIDATION,
    ];
    const URL_COUNT = 12;
    const ERROR_COUNT = 13;
    const WARNING_COUNT = 14;
    const REMOTE_TASK_COUNT = 0;
    const TASKS_WITH_ERRORS_COUNT = 0;
    const CANCELLED_TASK_COUNT = 0;
    const ENCODED_PARAMETERS = '';
    const AMENDMENTS = [];
    const COMPLETION_PERCENT = 25;
    const TASK_COUNT_BY_STATE = [
        'in_progress' => 0,
        'queued' => 0,
        'completed' => 0,
        'cancelled' => 0,
        'failed' => 0,
        'skipped' => 0,
    ];
    const REJECTION = null;

    private $testProperties = [
        'test_id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'user' => self::USER,
        'state' => self::STATE,
        'type' => self::TYPE,
        'taskTypes' => self::TASK_TYPES,
        'urlCount' => self::URL_COUNT,
        'errorCount' => self::ERROR_COUNT,
        'warningCount' => self::WARNING_COUNT,
        'remoteTaskCount' => self::REMOTE_TASK_COUNT,
        'tasksWithErrorsCount' => self::TASKS_WITH_ERRORS_COUNT,
        'cancelledTaskCount' => self::CANCELLED_TASK_COUNT,
        'encodedParameters' => self::ENCODED_PARAMETERS,
        'amendments' => self::AMENDMENTS,
        'completionPercent' => self::COMPLETION_PERCENT,
        'taskCountByState' => self::TASK_COUNT_BY_STATE,
        'rejection' => self::REJECTION,
    ];

    public function testGetScalarProperties()
    {
        $testModel = $this->createTest();
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals(self::TEST_ID, $decoratedTest->getTestId());
        $this->assertEquals(self::WEBSITE, $decoratedTest->getWebsite());
        $this->assertEquals(self::STATE, $decoratedTest->getState());
        $this->assertEquals(self::TASK_TYPES, $decoratedTest->getTaskTypes());
        $this->assertEquals(self::URL_COUNT, $decoratedTest->getUrlCount());
        $this->assertEquals(self::ERROR_COUNT, $decoratedTest->getErrorCount());
        $this->assertEquals(self::WARNING_COUNT, $decoratedTest->getWarningCount());
        $this->assertEquals(self::REMOTE_TASK_COUNT, $decoratedTest->getRemoteTaskCount());
        $this->assertEquals(self::AMENDMENTS, $decoratedTest->getAmendments());
        $this->assertEquals(self::COMPLETION_PERCENT, $decoratedTest->getCompletionPercent());
        $this->assertEquals(self::TASK_COUNT_BY_STATE, $decoratedTest->getTaskCountByState());
        $this->assertEquals(self::REJECTION, $decoratedTest->getRejection());
    }

    /**
     * @dataProvider getIsFullSiteIsSingleUrlDataProvider
     */
    public function testGetIsFullSiteIsSingleUrl(
        array $testProperties,
        bool $expectedIsFullSite,
        bool $expectedIsSingleUrl
    ) {
        $testModel = $this->createTest($testProperties);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals($expectedIsFullSite, $decoratedTest->isFullSite());
        $this->assertEquals($expectedIsSingleUrl, $decoratedTest->isSingleUrl());
    }

    public function getIsFullSiteIsSingleUrlDataProvider(): array
    {
        return [
            'full site' => [
                'testProperties' => [
                    'type' => TestEntity::TYPE_FULL_SITE,
                ],
                'expectedIsFullSite' => true,
                'expectedIsSingleUrl' => false,
            ],
            'single url' => [
                'testProperties' => [
                    'type' => TestEntity::TYPE_SINGLE_URL,
                ],
                'expectedIsFullSite' => false,
                'expectedIsSingleUrl' => true,
            ],
            'crawl' => [
                'testProperties' => [
                    'type' => 'crawl',
                ],
                'expectedIsFullSite' => false,
                'expectedIsSingleUrl' => false,
            ],
        ];
    }

    /**
     * @dataProvider getErrorCountByTaskTypeDataProvider
     */
    public function testGetErrorCountByTaskType(TestEntity $entity, array $expectedErrorCounts)
    {
        $testModel = $this->createTest([
            'entity' => $entity,
        ]);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        foreach ($expectedErrorCounts as $taskType => $expectedErrorCount) {
            $this->assertEquals($expectedErrorCount, $decoratedTest->getErrorCountByTaskType($taskType));
        }
    }

    public function getErrorCountByTaskTypeDataProvider(): array
    {
        return [
            'no tasks' => [
                'entity' => $this->createTestEntity(self::TEST_ID),
                'expectedErrorCounts' => [
                    Task::TYPE_HTML_VALIDATION => 0,
                    Task::TYPE_CSS_VALIDATION => 0,
                    Task::TYPE_LINK_INTEGRITY => 0,
                ],
            ],
            'single task, no output' => [
                'entity' => $this->createTestEntity(self::TEST_ID, [
                    new Task(),
                ]),
                'expectedErrorCounts' => [
                    Task::TYPE_HTML_VALIDATION => 0,
                    Task::TYPE_CSS_VALIDATION => 0,
                    Task::TYPE_LINK_INTEGRITY => 0,
                ],
            ],
            'single task, single html validation error' => [
                'entity' => $this->createTestEntity(self::TEST_ID, [
                    $this->createTask(
                        Task::TYPE_HTML_VALIDATION,
                        $this->createTaskOutput(1, 0)
                    ),
                ]),
                'expectedErrorCounts' => [
                    Task::TYPE_HTML_VALIDATION => 1,
                    Task::TYPE_CSS_VALIDATION => 0,
                    Task::TYPE_LINK_INTEGRITY => 0,
                ],
            ],
            'many tasks, variety of error counts' => [
                'entity' => $this->createTestEntity(self::TEST_ID, [
                    $this->createTask(
                        Task::TYPE_HTML_VALIDATION,
                        $this->createTaskOutput(1, 0)
                    ),
                    $this->createTask(
                        Task::TYPE_HTML_VALIDATION,
                        $this->createTaskOutput(2, 0)
                    ),
                    $this->createTask(
                        Task::TYPE_CSS_VALIDATION,
                        $this->createTaskOutput(4, 0)
                    ),
                    $this->createTask(
                        Task::TYPE_LINK_INTEGRITY,
                        $this->createTaskOutput(3, 0)
                    ),
                    $this->createTask(
                        Task::TYPE_LINK_INTEGRITY,
                        $this->createTaskOutput(3, 0)
                    ),
                ]),
                'expectedErrorCounts' => [
                    Task::TYPE_HTML_VALIDATION => 3,
                    Task::TYPE_CSS_VALIDATION => 4,
                    Task::TYPE_LINK_INTEGRITY => 6,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getErrorFreeTaskCountDataProvider
     */
    public function testGetErrorFreeTaskCount(
        int $remoteTaskCount,
        int $tasksWithErrorsCount,
        int $cancelledTaskCount,
        int $expectedErrorFreeTaskCount
    ) {
        $testModel = $this->createTest([
            'remoteTaskCount' => $remoteTaskCount,
            'tasksWithErrorsCount' => $tasksWithErrorsCount,
            'cancelledTaskCount' => $cancelledTaskCount,
        ]);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals($expectedErrorFreeTaskCount, $decoratedTest->getErrorFreeTaskCount());
    }

    public function getErrorFreeTaskCountDataProvider(): array
    {
        return [
            'no counts' => [
                'remoteTaskCount' => 0,
                'tasksWithErrorsCount' => 0,
                'cancelledTaskCount' => 0,
                'expectedErrorFreeTaskCount' => 0,
            ],
            'has remote tasks, none errored, none cancelled' => [
                'remoteTaskCount' => 10,
                'tasksWithErrorsCount' => 0,
                'cancelledTaskCount' => 0,
                'expectedErrorFreeTaskCount' => 10,
            ],
            'has remote tasks, some errored, none cancelled' => [
                'remoteTaskCount' => 10,
                'tasksWithErrorsCount' => 1,
                'cancelledTaskCount' => 0,
                'expectedErrorFreeTaskCount' => 9,
            ],
            'has remote tasks, none errored, some cancelled' => [
                'remoteTaskCount' => 10,
                'tasksWithErrorsCount' => 0,
                'cancelledTaskCount' => 2,
                'expectedErrorFreeTaskCount' => 8,
            ],
            'has remote tasks, some errored, some cancelled' => [
                'remoteTaskCount' => 10,
                'tasksWithErrorsCount' => 1,
                'cancelledTaskCount' => 2,
                'expectedErrorFreeTaskCount' => 7,
            ],
        ];
    }

    /**
     * @dataProvider getLocalTaskCountDataProvider
     */
    public function testGetLocalTaskCount(TestEntity $entity, int $expectedLocalTaskCount)
    {
        $testModel = $this->createTest([
            'entity' => $entity,
        ]);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals($expectedLocalTaskCount, $decoratedTest->getLocalTaskCount());
    }

    public function getLocalTaskCountDataProvider(): array
    {
        return [
            'no tasks' => [
                'entity' => $this->createTestEntity(1),
                'expectedLocalTaskCount' => 0,
            ],
            'one task' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                ]),
                'expectedLocalTaskCount' => 1,
            ],
            'three tasks' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                ]),
                'expectedLocalTaskCount' => 3,
            ],
        ];
    }

    /**
     * @dataProvider getParameterDataProvider
     */
    public function testGetParameter($encodedParameters, string $key, $expectedValue)
    {
        $testModel = $this->createTest([
            'encodedParameters' => $encodedParameters,
        ]);
        $remoteTest = new RemoteTest([]);

        $decoratedTest = new DecoratedTest($testModel, $remoteTest);

        $this->assertEquals($expectedValue, $decoratedTest->getParameter($key));
    }

    public function getParameterDataProvider(): array
    {
        return [
            'empty parameters' => [
                'encodedParameters' => '',
                'key' => 'key1',
                'value' => null,
            ],
            'key not present' => [
                'encodedParameters' => json_encode([
                    'key2' => 'value2',
                ]),
                'key' => 'key1',
                'value' => null,
            ],
            'key present' => [
                'encodedParameters' => json_encode([
                    'key1' => 'value1',
                    'key2' => 'value2',
                ]),
                'key' => 'key1',
                'value' => 'value1',
            ],
        ];
    }

    private function createTest(array $properties = []): TestModel
    {
        $properties = array_merge($this->testProperties, $properties);

        if (!isset($properties['entity'])) {
            $properties['entity'] = $this->createTestEntity(self::TEST_ID);
        }

        return new TestModel(
            $properties['entity'],
            $properties['website'],
            $properties['user'],
            $properties['state'],
            $properties['type'],
            $properties['taskTypes'],
            $properties['urlCount'],
            $properties['errorCount'],
            $properties['warningCount'],
            $properties['remoteTaskCount'],
            $properties['tasksWithErrorsCount'],
            $properties['cancelledTaskCount'],
            $properties['encodedParameters'],
            $properties['amendments'],
            $properties['completionPercent'],
            $properties['taskCountByState'],
            $properties['rejection']
        );
    }

    private function createTestEntity(int $testId, array $tasks = []): TestEntity
    {
        $entity = TestEntity::create($testId);

        foreach ($tasks as $task) {
            $entity->addTask($task);
        }

        return $entity;
    }

    private function createTask(string $type, ?Output $output = null): Task
    {
        $task = new Task();
        $task->setType($type);

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
