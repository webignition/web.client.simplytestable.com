<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Tests\Factory\OutputFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

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
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

    /**
     * @dataProvider getCollectionExistsByTestAndRemoteIdDataProvider
     *
     * @param array $testValuesCollection
     * @param int $testIndex
     * @param int[] $taskRemoteIds
     * @param array $expectedResult
     */
    public function testGetCollectionExistsByTestAndRemoteId(
        array $testValuesCollection,
        $testIndex,
        array $taskRemoteIds,
        array $expectedResult
    ) {
        $testFactory = new TestFactory($this->container);

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

    /**
     * @return array
     */
    public function getCollectionExistsByTestAndRemoteIdDataProvider()
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
     *
     * @param array $testValuesCollection
     * @param int $testIndex
     * @param int[] $taskRemoteIds
     * @param array $expectedTaskRemoteIds
     */
    public function testGetCollectionByTestAndRemoteId(
        array $testValuesCollection,
        $testIndex,
        array $taskRemoteIds,
        array $expectedTaskRemoteIds
    ) {
        $testFactory = new TestFactory($this->container);

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

    /**
     * @return array
     */
    public function getCollectionByTestAndRemoteIdDataProvider()
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
     *
     * @param array $outputValuesCollection
     * @param array $testValuesCollection
     * @param array $expectedOutputIndices
     */
    public function testFindUsedTaskOutputIds(
        array $outputValuesCollection,
        array $testValuesCollection,
        array $expectedOutputIndices
    ) {
        $outputFactory = new OutputFactory($this->container);
        $testFactory = new TestFactory($this->container);

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

    /**
     * @return array
     */
    public function findUsedTaskOutputIdsDataProvider()
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
     *
     * @param int $testIndex
     * @param array $expectedRemoteTaskIds
     */
    public function testFindRetrievedRemoteTaskIds($testIndex, array $expectedRemoteTaskIds)
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

        $testFactory = new TestFactory($this->container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIds = $this->taskRepository->findRetrievedRemoteTaskIds($test);

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    /**
     * @return array
     */
    public function findRetrievedRemoteTaskIdsDataProvider()
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
     *
     * @param int $testIndex
     * @param string $taskType
     * @param string[] $states
     * @param array $expectedRemoteTaskIds
     */
    public function testGetRemoteIdByTestAndTaskTypeIncludingStates(
        $testIndex,
        $taskType,
        $states,
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

        $testFactory = new TestFactory($this->container);

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

    /**
     * @return array
     */
    public function getRemoteIdByTestAndTaskTypeIncludingStatesDataProvider()
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
     *
     * @param int $testIndex
     * @param string $issueCount
     * @param string $issueType
     * @param string $taskType
     * @param string[] $states
     * @param array $expectedRemoteTaskIds
     */
    public function testGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
        $testIndex,
        $issueCount,
        $issueType,
        $taskType,
        $states,
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

        $outputFactory = new OutputFactory($this->container);

        $outputs = [];

        foreach ($outputValuesCollection as $outputValues) {
            $output = $outputFactory->create($outputValues);
            $outputs[] = $output;
        }

        $testValuesCollection = $this->populateTestValuesCollectionWithTaskOutputs($testValuesCollection, $outputs);

        $testFactory = new TestFactory($this->container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIds = $this->taskRepository->getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            $issueCount,
            $issueType,
            $taskType,
            $states
        );

        $this->assertEquals($expectedRemoteTaskIds, $remoteTaskIds);
    }

    /**
     * @return array
     */
    public function getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider()
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
     *
     * @param int $testIndex
     * @param string $issueCount
     * @param string $issueType
     * @param string $taskType
     * @param string[] $states
     * @param int $expectedRemoteIdCount
     */
    public function testGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
        $testIndex,
        $issueCount,
        $issueType,
        $taskType,
        $states,
        $expectedRemoteIdCount
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

        $outputFactory = new OutputFactory($this->container);

        $outputs = [];

        foreach ($outputValuesCollection as $outputValues) {
            $output = $outputFactory->create($outputValues);
            $outputs[] = $output;
        }

        $testValuesCollection = $this->populateTestValuesCollectionWithTaskOutputs($testValuesCollection, $outputs);

        $testFactory = new TestFactory($this->container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        $remoteTaskIdCount = $this->taskRepository->getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            $issueCount,
            $issueType,
            $taskType,
            $states
        );

        $this->assertEquals($expectedRemoteIdCount, $remoteTaskIdCount);
    }

    /**
     * @return array
     */
    public function getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesDataProvider()
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
     *
     * @param int $testIndex
     * @param string $taskType
     * @param string[] $states
     * @param int $expectedRemoteTaskCount
     */
    public function testGetRemoteIdCountByTestAndTaskTypeIncludingStates(
        $testIndex,
        $taskType,
        $states,
        $expectedRemoteTaskCount
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

        $testFactory = new TestFactory($this->container);

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

    /**
     * @return array
     */
    public function getRemoteIdCountByTestAndTaskTypeIncludingStatesDataProvider()
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

    /**
     * @param array $testValuesCollection
     * @param array $outputs
     *
     * @return array
     */
    private function populateTestValuesCollectionWithTaskOutputs(array $testValuesCollection, array $outputs)
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
