<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Tests\Factory\OutputFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

class TaskRepositoryTest extends BaseSimplyTestableTestCase
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
     * @dataProvider hasByTaskIdDataProvider
     *
     * @param int $remoteTaskId
     * @param bool $expectedHas
     */
    public function testHasByTaskId($remoteTaskId, $expectedHas)
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
        ];

        $testFactory = new TestFactory($this->container);
        foreach ($testValuesCollection as $testValues) {
            $testFactory->create($testValues);
        }

        $this->assertEquals($expectedHas, $this->taskRepository->hasByTaskId($remoteTaskId));
    }

    /**
     * @return array
     */
    public function hasByTaskIdDataProvider()
    {
        return [
            'has remote task 1' => [
                'remoteTaskId' => 1,
                'expectedHas' => true,
            ],
            'has remote task 2' => [
                'remoteTaskId' => 2,
                'expectedHas' => true,
            ],
            'does not have remote task 3' => [
                'remoteTaskId' => 3,
                'expectedHas' => false,
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
