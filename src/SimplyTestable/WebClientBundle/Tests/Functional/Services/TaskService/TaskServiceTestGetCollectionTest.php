<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;

class TaskServiceTestGetCollectionTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getCollectionDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValuesCollection
     * @param int $testIndex
     * @param int[] $remoteTaskIds
     * @param array $expectedTaskDataCollection
     */
    public function testGetCollection(
        array $httpFixtures,
        array $testValuesCollection,
        $testIndex,
        $remoteTaskIds,
        array $expectedTaskDataCollection
    ) {
        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $testFactory = new TestFactory($this->container);

        /* @var Test[] $tests */
        $tests = [];

        foreach ($testValuesCollection as $testValues) {
            $tests[] = $testFactory->create($testValues);
        }

        $test = $tests[$testIndex];

        /* @var Task[] $taskCollection */
        $taskCollection = $this->taskService->getCollection($test, $remoteTaskIds);

        $testTaskIds = [];
        foreach ($test->getTasks() as $task) {
            $testTaskIds[] = $task->getTaskId();
        }

        foreach ($taskCollection as $taskId => $task) {
            $expectedTaskData = $expectedTaskDataCollection[$taskId];

            $this->assertInstanceOf(Task::class, $task);
            $this->assertEquals($expectedTaskData['state'], $task->getState());
            $this->assertEquals($expectedTaskData['hasOutput'], $task->hasOutput());

            if ($expectedTaskData['hasOutput'] === true) {
                $expectedOutput = $expectedTaskData['output'];
                $output = $task->getOutput();

                $this->assertInstanceOf(Output::class, $output);

                $this->assertEquals($expectedOutput['output'], $output->getContent());
                $this->assertEquals($expectedOutput['errorCount'], $output->getErrorCount());
                $this->assertEquals($expectedOutput['warningCount'], $output->getWarningCount());
            }
        }
    }

    /**
     * @return array
     */
    public function getCollectionDataProvider()
    {
        return [
            'no remote task ids, none exist locally' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        2, 3,
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                        ],
                        [
                            'id' => 3,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'worker' => '',
                        ],
                    ]),
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => null,
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                ],
            ],
            'all remote task ids, none exist locally' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                        ],
                        [
                            'id' => 3,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'worker' => '',
                        ],
                    ]),
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => [2, 3],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                ],
            ],
            'all remote task ids, all exist locally, all finished' => [
                'httpFixtures' => [],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                                TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                                TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                            ],
                        ],
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => [2, 3],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                    ],
                ],
            ],
            'all remote task ids, all exist locally, some finished' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_IN_PROGRESS,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                        ],
                        [
                            'id' => 3,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_CANCELLED,
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'worker' => '',
                        ],
                        [
                            'id' => 4,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_FAILED_NO_RETRY_AVAILABLE,
                            'type' => Task::TYPE_LINK_INTEGRITY,
                            'worker' => '',
                        ],
                    ]),
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_STATE => Test::STATE_COMPLETED,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 4,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_LINK_INTEGRITY,
                            ],
                        ],
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => [2, 3, 4],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_CANCELLED,
                        'hasOutput' => false,
                    ],
                    3 => [
                        'state' => Task::STATE_CANCELLED,
                        'hasOutput' => false,
                    ],
                    4 => [
                        'state' => Task::STATE_FAILED,
                        'hasOutput' => false,
                    ],
                ],
            ],
            'all remote task ids, all exist locally, some finished, retrieved tasks have outputs' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                            'output' => [
                                'output' => '{"messages":[]}',
                                'content_type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ],
                        ],
                    ]),
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => [2],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => true,
                        'output' => [
                            'output' => '{"messages":[]}',
                            'errorCount' => 0,
                            'warningCount' => 0,
                        ],
                    ],
                ],
            ],
            'all remote task ids, all exist locally, some finished, retrieved tasks have identical outputs' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                            'output' => [
                                'output' => '{"messages":[]}',
                                'content_type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ],
                        ],
                        [
                            'id' => 3,
                            'url' => 'http://example.com/bar/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'worker' => '',
                            'output' => [
                                'output' => '{"messages":[]}',
                                'content_type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ],
                        ],
                    ]),
                ],
                'testValuesCollection' => [
                    [
                        TestFactory::KEY_TEST_ID => 1,
                        TestFactory::KEY_TASKS => [
                            [
                                TaskFactory::KEY_TASK_ID => 2,
                                TaskFactory::KEY_URL => 'http://example.com/foo/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                            ],
                            [
                                TaskFactory::KEY_TASK_ID => 3,
                                TaskFactory::KEY_URL => 'http://example.com/bar/',
                                TaskFactory::KEY_STATE => Task::STATE_IN_PROGRESS,
                                TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                    ],
                ],
                'testIndex' => 0,
                'remoteTaskIds' => [2],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => true,
                        'output' => [
                            'output' => '{"messages":[]}',
                            'errorCount' => 0,
                            'warningCount' => 0,
                        ],
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => true,
                        'output' => [
                            'output' => '{"messages":[]}',
                            'errorCount' => 0,
                            'warningCount' => 0,
                        ],
                    ],
                ],
            ],
        ];
    }
}
