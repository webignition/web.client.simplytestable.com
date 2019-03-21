<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\Test as TestModel;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;

class TaskServiceGetCollectionTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getCollectionDataProvider
     */
    public function testGetCollection(
        array $httpFixtures,
        array $testValues,
        string $testState,
        ?array $remoteTaskIds,
        array $expectedTaskDataCollection,
        array $expectedRequestUrls
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

        /* @var Task[] $taskCollection */
        $taskCollection = $this->taskService->getCollection($test, $testState, $remoteTaskIds);

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

        $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrlsAsStrings());
    }

    public function getCollectionDataProvider(): array
    {
        return [
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
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'testState' => TestModel::STATE_COMPLETED,
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
                'expectedRequestUrls' => [
                    'http://null/job/1/tasks/',
                ],
            ],
            'all remote task ids, all exist locally, all finished' => [
                'httpFixtures' => [],
                'testValues' => [
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
                'testState' => TestModel::STATE_COMPLETED,
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
                'expectedRequestUrls' => [],
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
                'testValues' => [
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
                'testState' => TestModel::STATE_COMPLETED,
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
                'expectedRequestUrls' => [
                    'http://null/job/1/tasks/',
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
                'testValues' => [
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
                'testState' => TestModel::STATE_COMPLETED,
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
                'expectedRequestUrls' => [
                    'http://null/job/1/tasks/',
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
                'testValues' => [
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
                'testState' => TestModel::STATE_COMPLETED,
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
                'expectedRequestUrls' => [
                    'http://null/job/1/tasks/',
                ],
            ],
        ];
    }
}
