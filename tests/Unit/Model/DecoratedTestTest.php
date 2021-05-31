<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;

class DecoratedTestTest extends \PHPUnit\Framework\TestCase
{
    const TEST_ID = 1;
    const WEBSITE = 'http://example.com/';
    const USER = 'user@example.com';
    const STATE = TestModel::STATE_COMPLETED;
    const TYPE = TestModel::TYPE_FULL_SITE;
    const TASK_TYPES = [
        Task::TYPE_HTML_VALIDATION,
    ];
    const URL_COUNT = 12;
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
    const CRAWL_DATA = [];
    const REJECTION = [];
    const IS_PUBLIC = false;
    const TASK_OPTIONS = [];
    const OWNERS = [
        self::USER,
    ];
    const START_DATE_TIME = null;
    const END_DATE_TIME = null;

    private $testProperties = [
        'test_id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'user' => self::USER,
        'state' => self::STATE,
        'type' => self::TYPE,
        'taskTypes' => self::TASK_TYPES,
        'urlCount' => self::URL_COUNT,
        'remoteTaskCount' => self::REMOTE_TASK_COUNT,
        'tasksWithErrorsCount' => self::TASKS_WITH_ERRORS_COUNT,
        'cancelledTaskCount' => self::CANCELLED_TASK_COUNT,
        'encodedParameters' => self::ENCODED_PARAMETERS,
        'amendments' => self::AMENDMENTS,
        'completionPercent' => self::COMPLETION_PERCENT,
        'taskCountByState' => self::TASK_COUNT_BY_STATE,
        'rejection' => self::REJECTION,
        'crawlData' => self::CRAWL_DATA,
        'isPublic' => self::IS_PUBLIC,
        'taskOptions' => self::TASK_OPTIONS,
        'owners' => self::OWNERS,
        'startDateTime' => self::START_DATE_TIME,
        'endDateTime' => self::END_DATE_TIME,
    ];

    public function testGetScalarProperties()
    {
        $entity = $this->createTestEntity(1);
        $testModel = $this->createTest([
            'entity' => $entity,
        ]);

        $decoratedTest = new DecoratedTest($testModel);

        $this->assertEquals(self::TEST_ID, $decoratedTest->getTestId());
        $this->assertEquals(self::WEBSITE, $decoratedTest->getWebsite());
        $this->assertEquals(self::STATE, $decoratedTest->getState());
        $this->assertEquals(self::TASK_TYPES, $decoratedTest->getTaskTypes());
        $this->assertEquals(self::URL_COUNT, $decoratedTest->getUrlCount());
        $this->assertEquals(0, $decoratedTest->getErrorCount());
        $this->assertEquals(0, $decoratedTest->getWarningCount());
        $this->assertEquals(self::REMOTE_TASK_COUNT, $decoratedTest->getRemoteTaskCount());
        $this->assertEquals(self::AMENDMENTS, $decoratedTest->getAmendments());
        $this->assertEquals(self::COMPLETION_PERCENT, $decoratedTest->getCompletionPercent());
        $this->assertEquals(self::TASK_COUNT_BY_STATE, $decoratedTest->getTaskCountByState());
        $this->assertEquals(self::REJECTION, $decoratedTest->getRejection());
        $this->assertEquals(self::START_DATE_TIME, $decoratedTest->getStartDateTime());
        $this->assertEquals(self::END_DATE_TIME, $decoratedTest->getEndDateTime());
    }

    /**
     * @dataProvider getIsFullSiteIsSingleUrlDataProvider
     */
    public function testGetIsFullSiteIsSingleUrl(
        array $testProperties,
        bool $expectedIsFullSite,
        bool $expectedIsSingleUrl
    ) {
        $decoratedTest = new DecoratedTest($this->createTest($testProperties));

        $this->assertEquals($expectedIsFullSite, $decoratedTest->isFullSite());
        $this->assertEquals($expectedIsSingleUrl, $decoratedTest->isSingleUrl());
    }

    public function getIsFullSiteIsSingleUrlDataProvider(): array
    {
        return [
            'full site' => [
                'testProperties' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                ],
                'expectedIsFullSite' => true,
                'expectedIsSingleUrl' => false,
            ],
            'single url' => [
                'testProperties' => [
                    'type' => TestModel::TYPE_SINGLE_URL,
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
        $decoratedTest = new DecoratedTest($this->createTest([
            'entity' => $entity,
        ]));

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
        $decoratedTest = new DecoratedTest($this->createTest([
            'remoteTaskCount' => $remoteTaskCount,
            'tasksWithErrorsCount' => $tasksWithErrorsCount,
            'cancelledTaskCount' => $cancelledTaskCount,
        ]));

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
        $decoratedTest = new DecoratedTest($this->createTest([
            'entity' => $entity,
        ]));

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
        $decoratedTest = new DecoratedTest($this->createTest([
            'encodedParameters' => $encodedParameters,
        ]));

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

    /**
     * @dataProvider requiresRemoteTasksDataProvider
     */
    public function testRequiresRemoteTasks(
        TestEntity $entity,
        int $remoteTaskCount,
        bool $expectedRequiresRemoteTasks
    ) {
        $decoratedTest = new DecoratedTest($this->createTest([
            'entity' => $entity,
            'remoteTaskCount' => $remoteTaskCount,
        ]));

        $this->assertEquals($expectedRequiresRemoteTasks, $decoratedTest->requiresRemoteTasks());
    }

    public function requiresRemoteTasksDataProvider(): array
    {
        return [
            'no local tasks, no remote tasks' => [
                'entity' => $this->createTestEntity(1),
                'remoteTaskCount' => 0,
                'expectedRequiresRemoteTasks' => false,
            ],
            'local task count === remote task count' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                ]),
                'remoteTaskCount' => 3,
                'expectedRequiresRemoteTasks' => false,
            ],
            'local task count < remote task count' => [
                'entity' => $this->createTestEntity(1, [
                    $this->createTask(Task::TYPE_HTML_VALIDATION),
                ]),
                'remoteTaskCount' => 3,
                'expectedRequiresRemoteTasks' => true,
            ],
        ];
    }

    /**
     * @dataProvider getCrawlDataDataProvider
     */
    public function testGetCrawlData(array $crawlData)
    {
        $decoratedTest = new DecoratedTest($this->createTest([
            'crawlData' => $crawlData,
        ]));

        $this->assertEquals($crawlData, $decoratedTest->getCrawlData());
    }

    public function getCrawlDataDataProvider(): array
    {
        return [
            'empty crawl data' => [
                'crawlData' => [],
            ],
            'non-empty crawl data' => [
                'crawlData' => [
                    'state' => 'in-progress',
                    'processed_url_count' => 10,
                    'discovered_url_count' => 20,
                    'limit' => 30,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getFormattedWebsiteDataProvider
     */
    public function testGetFormattedWebsite(string $website, string $expectedWebsite)
    {
        $decoratedTest = new DecoratedTest($this->createTest([
            'website' => $website,
        ]));

        $this->assertEquals($expectedWebsite, $decoratedTest->getFormattedWebsite());
    }

    public function getFormattedWebsiteDataProvider(): array
    {
        return [
            'no percent-encoded characters' => [
                'website' => 'http://example.com/',
                'expectedWebsite' => 'http://example.com/',
            ],
            'with percent-encoded characters' => [
                'website' => 'http://example.com/%3A',
                'expectedWebsite' => 'http://example.com/:',
            ],
        ];
    }

    /**
     * @dataProvider jsonSerializeDataProvider
     */
    public function testJsonSerialize(array $testData, array $expectedSerializedArray)
    {
        $decoratedTest = new DecoratedTest($this->createTest($testData));

        $this->assertEquals($expectedSerializedArray, $decoratedTest->jsonSerialize());
    }

    public function jsonSerializeDataProvider(): array
    {
        return [
            'default' => [
                'testData' => [
                    'user' => 'user@example.com',
                    'website' => 'http://example.com/',
                    'state' => TestModel::STATE_COMPLETED,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    'remoteTaskCount' => 100,
                    'completionPercent' => 33,
                    'taskCountByState' => [
                        'in_progress' => 1,
                        'queued' => 2,
                        'completed' => 3,
                        'cancelled' => 4,
                        'failed' => 5,
                        'skipped' => 6,
                    ],
                    'amendments' => [],
                ],
                'expectedSerializedArray' => [
                    'user' => 'user@example.com',
                    'website' => 'http://example.com/',
                    'state' => TestModel::STATE_COMPLETED,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    'task_count' => 100,
                    'completion_percent' => 33,
                    'task_count_by_state' => [
                        'in_progress' => 1,
                        'queued' => 2,
                        'completed' => 3,
                        'cancelled' => 4,
                        'failed' => 5,
                        'skipped' => 6,
                    ],
                    'amendments' => [],
                ],
            ],

        ];
    }

    public function testGetHash()
    {
        /* @var DecoratedTest[] $decoratedTests */
        $decoratedTests = [
            'default' => new DecoratedTest($this->createTest([
                'entity' => $this->createTestEntity(1),
                'state' => TestModel::STATE_COMPLETED,
                'remoteTaskCount' => 0,
            ])),
            'state changes hash' => new DecoratedTest($this->createTest([
                'entity' => $this->createTestEntity(1),
                'state' => TestModel::STATE_IN_PROGRESS,
                'remoteTaskCount' => 0,
            ])),
            'remoteTaskCount != localTaskCount changes hash' => new DecoratedTest($this->createTest([
                'entity' => $this->createTestEntity(1),
                'state' => TestModel::STATE_COMPLETED,
                'remoteTaskCount' => 1,
            ])),
        ];

        $hashes = [];

        foreach ($decoratedTests as $decoratedTest) {
            $hash = $decoratedTest->getHash();

            $this->assertRegExp('/[a-f0-9]{32}/', $hash);
            $this->assertNotContains($hash, $hashes);

            $hashes[] = $hash;
        }
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
            $properties['remoteTaskCount'],
            $properties['tasksWithErrorsCount'],
            $properties['cancelledTaskCount'],
            $properties['encodedParameters'],
            $properties['amendments'],
            $properties['completionPercent'],
            $properties['taskCountByState'],
            $properties['crawlData'],
            $properties['rejection'],
            $properties['isPublic'],
            $properties['taskOptions'],
            $properties['owners'],
            $properties['startDateTime'],
            $properties['endDateTime']
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
