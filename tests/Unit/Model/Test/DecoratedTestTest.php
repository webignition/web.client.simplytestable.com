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
            $properties['warningCount']
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
