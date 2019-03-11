<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Entity\TimePeriod;
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
        ?array $remoteTaskIds,
        array $expectedTaskDataCollection,
        array $expectedRequestUrls
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

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

            $expectedTimePeriodData = $expectedTaskData['timePeriod'];
            $timePeriod = $task->getTimePeriod();

            $this->assertInstanceOf(TimePeriod::class, $timePeriod);

            if (is_null($expectedTimePeriodData['startDateTime'])) {
                $this->assertNull($timePeriod->getStartDateTime());
            } else {
                $this->assertEquals(
                    $expectedTimePeriodData['startDateTime'],
                    $timePeriod->getStartDateTime()->format(DATE_ATOM)
                );
            }

            if (is_null($expectedTimePeriodData['endDateTime'])) {
                $this->assertNull($timePeriod->getEndDateTime());
            } else {
                $this->assertEquals(
                    $expectedTimePeriodData['endDateTime'],
                    $timePeriod->getEndDateTime()->format(DATE_ATOM)
                );
            }
        }

        $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrlsAsStrings());
    }

    public function getCollectionDataProvider(): array
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
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'remoteTaskIds' => null,
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                ],
                'expectedRequestUrls' => [
                    'http://null/job/1/tasks/ids/',
                    'http://null/job/1/tasks/',
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
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'remoteTaskIds' => [2, 3],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
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
                'remoteTaskIds' => [2, 3],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                    3 => [
                        'state' => Task::STATE_COMPLETED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
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
                'remoteTaskIds' => [2, 3, 4],
                'expectedTaskDataCollection' => [
                    2 => [
                        'state' => Task::STATE_CANCELLED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                    3 => [
                        'state' => Task::STATE_CANCELLED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
                    ],
                    4 => [
                        'state' => Task::STATE_FAILED,
                        'hasOutput' => false,
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
                        ],
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
                            'time_period' => [
                                'start_date_time' => '2018-01-10T10:55:12+00:00',
                                'end_date_time' => '2018-01-10T10:55:13+00:00',
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
                        'hasTimePeriod' => true,
                        'timePeriod' => [
                            'startDateTime' => '2018-01-10T10:55:12+00:00',
                            'endDateTime' => '2018-01-10T10:55:13+00:00',
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
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
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
                        'timePeriod' => [
                            'startDateTime' => null,
                            'endDateTime' => null,
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
