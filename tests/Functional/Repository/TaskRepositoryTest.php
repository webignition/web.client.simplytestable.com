<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Repository\TaskRepository;
use App\Tests\Factory\OutputFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;
use App\Tests\Functional\AbstractBaseTestCase;

class TaskRepositoryTest extends AbstractBaseTestCase
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

    /**
     * @dataProvider getCollectionExistsByTestAndRemoteIdDataProvider
     */
    public function testGetCollectionExistsByTestAndRemoteId(
        array $testValuesCollection,
        int $testIndex,
        array $taskRemoteIds,
        array $expectedResult
    ) {
        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $testIds = [];
        foreach ($tests as $test) {
            $testIds[] = $test->getId();
        }

        $test = $tests[$testIndex];

        $result = $this->taskRepository->getCollectionExistsByTestAndRemoteId($test, $taskRemoteIds);

        $this->assertEquals($expectedResult, $result);
    }

    public function getCollectionExistsByTestAndRemoteIdDataProvider(): array
    {
        return [
            'single test, no tasks, no remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [],
                'expectedResult' => [],
            ],
            'single test, single task, no remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [],
                'expectedResult' => [],
            ],
            'single test, single task, remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [1, 2, 3],
                'expectedResult' => [
                    1 => true,
                    2 => false,
                    3 => false,
                ],
            ],
            'many tests, many tasks, remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 5,
                            ]
                        ],
                    ],
                    [
                        TestFactory::KEY_TEST_ID => 2,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 4,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 6,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [1, 2, 3],
                'expectedResult' => [
                    1 => true,
                    2 => false,
                    3 => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getCollectionByTestAndRemoteIdDataProvider
     */
    public function testGetCollectionByTestAndRemoteId(
        array $testValuesCollection,
        int $testIndex,
        array $taskRemoteIds,
        array $expectedTaskRemoteIds
    ) {
        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $testIds = [];
        foreach ($tests as $test) {
            $testIds[] = $test->getId();
        }

        $test = $tests[$testIndex];

        $tasks = $this->taskRepository->getCollectionByTestAndRemoteId($test, $taskRemoteIds);

        $retrievedTaskRemoteIds = [];

        foreach ($tasks as $task) {
            $retrievedTaskRemoteIds[] = $task->getTaskId();
        }

        $this->assertEquals($expectedTaskRemoteIds, $retrievedTaskRemoteIds);
    }

    public function getCollectionByTestAndRemoteIdDataProvider(): array
    {
        return [
            'single test, no tasks, no remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [],
                'expectedTaskRemoteIds' => [],
            ],
            'single test, single task, no remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [],
                'expectedTaskRemoteIds' => [
                    1,
                ],
            ],
            'single test, single task, remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [1, 2, 3],
                'expectedTaskRemoteIds' => [
                    1,
                ],
            ],
            'many tests, many tasks, remote task ids' => [
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 5,
                            ]
                        ],
                    ],
                    [
                        TestFactory::KEY_TEST_ID => 2,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 4,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 6,
                            ]
                        ],
                    ],
                ],
                'testIndex' => 0,
                'taskRemoteIds' => [1, 2, 3, 4, 5, 6],
                'expectedTaskRemoteIds' => [
                    1, 3, 5,
                ],
            ],
        ];
    }

    /**
     * @dataProvider findUsedTaskOutputIdsDataProvider
     */
    public function testFindUsedTaskOutputIds(
        array $outputValuesCollection,
        array $testValuesCollection,
        array $expectedOutputIndices
    ) {
        $outputFactory = new OutputFactory(self::$container);
        $testFactory = new TestFactory(self::$container);

        $outputs = [];
        $outputIds = [];
        $tests = [];

        foreach ($outputValuesCollection as $outputValues) {
            $output = $outputFactory->create($outputValues);
            $outputs[] = $output;
            $outputIds[] = $output->getId();
        }

        $testValuesCollection = $this->populateTestValuesCollectionWithTaskOutputs($testValuesCollection, $outputs);

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $expectedOutputIds = [];

        foreach ($expectedOutputIndices as $expectedOutputIndex) {
            $expectedOutputIds[] = $outputIds[$expectedOutputIndex];
        }

        $usedOutputIds = $this->taskRepository->findUsedTaskOutputIds();

        $this->assertEquals($expectedOutputIds, $usedOutputIds);
    }

    public function findUsedTaskOutputIdsDataProvider(): array
    {
        return [
            'no output, no tasks' => [
                'outputValuesCollection' => [],
                'testValuesCollection' => [],
                'expectedOutputIndices' => [],
            ],
            'output, no tasks' => [
                'outputValuesCollection' => [
                    [],
                ],
                'testValuesCollection' => [],
                'expectedOutputIndices' => [],
            ],
            'no used output' => [
                'outputValuesCollection' => [
                    [],
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                            ]
                        ],
                    ],
                ],
                'expectedOutputIndices' => [],
            ],
            'some used output' => [
                'outputValuesCollection' => [
                    [],
                    [],
                    [],
                    [],
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 1,
                                TaskFactory::KEY_OUTPUT => 0,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                                TaskFactory::KEY_OUTPUT => 1,
                            ],
                        ],
                    ],
                    [
                        TestFactory::KEY_TEST_ID => 2,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 4,
                                TaskFactory::KEY_OUTPUT => 3,
                            ],
                        ],
                    ],
                ],
                'expectedOutputIndices' => [
                    0, 1, 3
                ],
            ],
        ];
    }

    /**
     * @dataProvider findRetrievedRemoteTaskIdsDataProvider
     */
    public function testFindRetrievedRemoteTaskIds(int $testIndex, array $expectedRemoteTaskIds)
    {
        $testValuesCollection = [
            [
                TestFactory::KEY_TEST_ID => 1,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 2,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 3,
                TestFactory::KEY_TASKS => [],
            ],
        ];

        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIds = $this->taskRepository->findRetrievedRemoteTaskIds($test);

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    public function findRetrievedRemoteTaskIdsDataProvider(): array
    {
        return [
            'test 0' => [
                'testIndex' => 0,
                'expectedRemoteTaskIds' => [
                    1, 2,
                ],
            ],
            'test 1' => [
                'testIndex' => 1,
                'expectedRemoteTaskIds' => [
                    3, 4,
                ],
            ],
            'test 2' => [
                'testIndex' => 2,
                'expectedRemoteTaskIds' => [],
            ],
        ];
    }

    /**
     * @dataProvider getRemoteIdByTestAndTaskTypeIncludingStatesDataProvider
     */
    public function testGetRemoteIdByTestAndTaskTypeIncludingStates(
        int $testIndex,
        ?string $taskType,
        array $states,
        array $expectedRemoteTaskIds
    ) {
        $testValuesCollection = [
            [
                TestFactory::KEY_TEST_ID => 1,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        TaskFactory::KEY_STATE => Task::STATE_CANCELLED,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 2,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                    ],
                ],
            ],
        ];

        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIds = $this->taskRepository->getRemoteIdByTestAndTaskTypeIncludingStates(
            $test,
            $taskType,
            $states
        );

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    public function getRemoteIdByTestAndTaskTypeIncludingStatesDataProvider(): array
    {
        return [
            'testId 1; states: completed' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_COMPLETED,
                ],
                'expectedRemoteTaskIds' => [
                    1,
                ],
            ],
            'testId 1; states: cancelled' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_CANCELLED,
                ],
                'expectedRemoteTaskIds' => [
                    2,
                ],
            ],
            'testId 1; states: completed, queued' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_COMPLETED,
                    Task::STATE_CANCELLED,
                ],
                'expectedRemoteTaskIds' => [
                    1, 2,
                ],
            ],
            'testId 2; states: in-progress' => [
                'testIndex' => 1,
                'taskType' => null,
                'states' => [
                    Task::STATE_IN_PROGRESS,
                ],
                'expectedRemoteTaskIds' => [
                    3, 4,
                ],
            ],
            'testId 2; taskType: HTML Validation' => [
                'testIndex' => 1,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'states' => [
                    Task::STATE_IN_PROGRESS,
                ],
                'expectedRemoteTaskIds' => [
                    3,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider
     */
    public function testGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
        int $testIndex,
        string $issueCount,
        string $issueType,
        ?string $taskType,
        array $states,
        array $expectedRemoteTaskIds
    ) {
        $outputValuesCollection = [
            [
                OutputFactory::KEY_ERROR_COUNT => 0,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 1,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 0,
                OutputFactory::KEY_WARNING_COUNT => 1,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 1,
                OutputFactory::KEY_WARNING_COUNT => 1,
            ],
        ];

        $testValuesCollection = [
            [
                TestFactory::KEY_TEST_ID => 1,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                        TaskFactory::KEY_OUTPUT => 0,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        TaskFactory::KEY_STATE => Task::STATE_CANCELLED,
                        TaskFactory::KEY_OUTPUT => 1,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_AWAITING_CANCELLATION,
                        TaskFactory::KEY_OUTPUT => 1,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 2,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                        TaskFactory::KEY_OUTPUT => 2,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                        TaskFactory::KEY_OUTPUT => 3,
                    ],
                ],
            ],
        ];

        $outputFactory = new OutputFactory(self::$container);

        $outputs = [];

        foreach ($outputValuesCollection as $outputValues) {
            $output = $outputFactory->create($outputValues);
            $outputs[] = $output;
        }

        $testValuesCollection = $this->populateTestValuesCollectionWithTaskOutputs($testValuesCollection, $outputs);

        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIds = $this->taskRepository->getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            $states,
            $taskType,
            $issueCount,
            $issueType
        );

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    public function getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider(): array
    {
        return [
            'test 0 without errors; tasktype=null' => [
                'testIndex' => 0,
                'issueCount' => '= 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteTaskIds' => [
                    1,
                ],
            ],
            'test 0 without errors; tasktype=""' => [
                'testIndex' => 0,
                'issueCount' => '= 0',
                'issueType' => 'error',
                'taskType' => '',
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteTaskIds' => [
                    1,
                ],
            ],
            'test 0 with errors' => [
                'testIndex' => 0,
                'issueCount' => '> 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteTaskIds' => [
                    2, 3,
                ],
            ],
            'test 0 with errors, excluding states: cancelled, awaiting-cancellation' => [
                'testIndex' => 0,
                'issueCount' => '> 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_CANCELLED,
                    Task::STATE_AWAITING_CANCELLATION,
                ],
                'expectedRemoteTaskIds' => [],
            ],
            'test 1 with warnings' => [
                'testIndex' => 1,
                'issueCount' => '> 0',
                'issueType' => 'warning',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteTaskIds' => [
                    3, 4,
                ],
            ],
            'test 1 with warnings, taskType: CSS validation' => [
                'testIndex' => 1,
                'issueCount' => '> 0',
                'issueType' => 'warning',
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteTaskIds' => [
                    4,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider
     */
    public function testGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
        int $testIndex,
        string $issueCount,
        string $issueType,
        ?string $taskType,
        array $states,
        int $expectedRemoteIdCount
    ) {
        $outputValuesCollection = [
            [
                OutputFactory::KEY_ERROR_COUNT => 0,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 1,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 0,
                OutputFactory::KEY_WARNING_COUNT => 1,
            ],
            [
                OutputFactory::KEY_ERROR_COUNT => 1,
                OutputFactory::KEY_WARNING_COUNT => 1,
            ],
        ];

        $testValuesCollection = [
            [
                TestFactory::KEY_TEST_ID => 1,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                        TaskFactory::KEY_OUTPUT => 0,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        TaskFactory::KEY_STATE => Task::STATE_CANCELLED,
                        TaskFactory::KEY_OUTPUT => 1,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_AWAITING_CANCELLATION,
                        TaskFactory::KEY_OUTPUT => 1,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 2,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                        TaskFactory::KEY_OUTPUT => 2,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                        TaskFactory::KEY_OUTPUT => 3,
                    ],
                ],
            ],
        ];

        $outputFactory = new OutputFactory(self::$container);

        $outputs = [];

        foreach ($outputValuesCollection as $outputValues) {
            $output = $outputFactory->create($outputValues);
            $outputs[] = $output;
        }

        $testValuesCollection = $this->populateTestValuesCollectionWithTaskOutputs($testValuesCollection, $outputs);

        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIdCount = $this->taskRepository->getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            $states,
            $taskType,
            $issueCount,
            $issueType
        );

        $this->assertEquals($expectedRemoteIdCount, $remoteTaskIdCount);
    }

    public function getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider(): array
    {
        return [
            'test 0 without errors; taskType=null' => [
                'testIndex' => 0,
                'issueCount' => '= 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteIdCount' => 1,
            ],
            'test 0 without errors; taskType=""' => [
                'testIndex' => 0,
                'issueCount' => '= 0',
                'issueType' => 'error',
                'taskType' => '',
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteIdCount' => 1,
            ],
            'test 0 with errors' => [
                'testIndex' => 0,
                'issueCount' => '> 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteIdCount' => 2,
            ],
            'test 0 with errors, excluding states: cancelled, awaiting-cancellation' => [
                'testIndex' => 0,
                'issueCount' => '> 0',
                'issueType' => 'error',
                'taskType' => null,
                'states' => [
                    Task::STATE_CANCELLED,
                    Task::STATE_AWAITING_CANCELLATION,
                ],
                'expectedRemoteIdCount' => 0,
            ],
            'test 1 with warnings' => [
                'testIndex' => 1,
                'issueCount' => '> 0',
                'issueType' => 'warning',
                'taskType' => null,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteIdCount' => 2,
            ],
            'test 1 with warnings, taskType: CSS validation' => [
                'testIndex' => 1,
                'issueCount' => '> 0',
                'issueType' => 'warning',
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'states' => [
                    Task::STATE_QUEUED,
                ],
                'expectedRemoteIdCount' => 1,
            ],
        ];
    }

    /**
     * @dataProvider getRemoteIdCountByTestAndTaskTypeIncludingStatesDataProvider
     */
    public function testGetRemoteIdCountByTestAndTaskTypeIncludingStates(
        int $testIndex,
        ?string $taskType,
        array $states,
        int $expectedRemoteTaskCount
    ) {
        $testValuesCollection = [
            [
                TestFactory::KEY_TEST_ID => 1,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        TaskFactory::KEY_STATE => Task::STATE_CANCELLED,
                    ],
                ],
            ],
            [
                TestFactory::KEY_TEST_ID => 2,
                TestFactory::KEY_TASKS => [
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                        TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                    ],
                ],
            ],
        ];

        $testFactory = new TestFactory(self::$container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIdCount = $this->taskRepository->getRemoteIdCountByTestAndTaskTypeIncludingStates(
            $test,
            $taskType,
            $states
        );

        $this->assertEquals($expectedRemoteTaskCount, $remoteTaskIdCount);
    }

    public function getRemoteIdCountByTestAndTaskTypeIncludingStatesDataProvider(): array
    {
        return [
            'testId 1; states: completed' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_COMPLETED,
                ],
                'expectedRemoteTaskIdCount' => 1,
            ],
            'testId 1; states: cancelled' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_CANCELLED,
                ],
                'expectedRemoteTaskIdCount' => 1,
            ],
            'testId 1; states: completed, queued' => [
                'testIndex' => 0,
                'taskType' => null,
                'states' => [
                    Task::STATE_COMPLETED,
                    Task::STATE_CANCELLED,
                ],
                'expectedRemoteTaskIdCount' => 2,
            ],
            'testId 2; states: in-progress' => [
                'testIndex' => 1,
                'taskType' => null,
                'states' => [
                    Task::STATE_IN_PROGRESS,
                ],
                'expectedRemoteTaskIdCount' => 2,
            ],
            'testId 2; taskType: HTML Validation' => [
                'testIndex' => 1,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'states' => [
                    Task::STATE_IN_PROGRESS,
                ],
                'expectedRemoteTaskIdCount' => 1,
            ],
        ];
    }

    private function populateTestValuesCollectionWithTaskOutputs(array $testValuesCollection, array $outputs): array
    {
        foreach ($testValuesCollection as $testValuesIndex => $testValues) {
            if (isset($testValues[TestFactory::KEY_TASKS])) {
                $taskValuesCollection = $testValues[TestFactory::KEY_TASKS];

                foreach ($taskValuesCollection as $taskValuesIndex => $taskValues) {
                    if (isset($taskValues[TaskFactory::KEY_OUTPUT])) {
                        $taskValues[TaskFactory::KEY_OUTPUT] = $outputs[$taskValues[TaskFactory::KEY_OUTPUT]];
                    }

                    $taskValuesCollection[$taskValuesIndex] = $taskValues;
                }

                $testValues[TestFactory::KEY_TASKS] = $taskValuesCollection;
            }

            $testValuesCollection[$testValuesIndex] = $testValues;
        }

        return $testValuesCollection;
    }
}
