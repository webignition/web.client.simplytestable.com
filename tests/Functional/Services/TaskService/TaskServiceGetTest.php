<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Task;
use App\Model\Test as TestModel;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;

class TaskServiceGetTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider getCollectionDataProvider
     */
    public function testGet(
        array $httpFixtures,
        array $testValues,
        string $testState,
        int $remoteTaskId,
        ?array $expectedTaskData
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testFactory = new TestFactory(self::$container);

        $test = $testFactory->create($testValues);

        /* @var Task $task */
        $task = $this->taskService->get($test, $testState, $remoteTaskId);

        if (is_null($expectedTaskData)) {
            $this->assertNull($task);
        } else {
            $this->assertInstanceOf(Task::class, $task);

            $this->assertEquals($expectedTaskData['id'], $task->getTaskId());
            $this->assertEquals($expectedTaskData['state'], $task->getState());
            $this->assertEquals($expectedTaskData['url'], $task->getUrl());
            $this->assertEquals($expectedTaskData['type'], $task->getType());
        }
    }

    public function getCollectionDataProvider(): array
    {
        return [
            'not exist locally, not exist remotely' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'testState' => TestModel::STATE_COMPLETED,
                'remoteTaskId' => 2,
                'expectedTaskData' => null,
            ],
            'not exist locally, exists remotely' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_COMPLETED,
                            'type' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'testState' => TestModel::STATE_COMPLETED,
                'remoteTaskId' => 2,
                'expectedTaskData' => [
                    'id' => 2,
                    'url' => 'http://example.com/foo/',
                    'state' => Task::STATE_COMPLETED,
                    'type' => Task::TYPE_HTML_VALIDATION,
                ],
            ],
            'exists locally, test in-progress' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_IN_PROGRESS,
                            'type' => Task::TYPE_HTML_VALIDATION,
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
                'testState' => TestModel::STATE_IN_PROGRESS,
                'remoteTaskId' => 2,
                'expectedTaskData' => [
                    'id' => 2,
                    'url' => 'http://example.com/foo/',
                    'state' => Task::STATE_IN_PROGRESS,
                    'type' => Task::TYPE_HTML_VALIDATION,
                ],
            ],
            'exists locally, test completed' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com/foo/',
                            'state' => Task::STATE_IN_PROGRESS,
                            'type' => Task::TYPE_HTML_VALIDATION,
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
                'remoteTaskId' => 2,
                'expectedTaskData' => [
                    'id' => 2,
                    'url' => 'http://example.com/foo/',
                    'state' => Task::STATE_CANCELLED,
                    'type' => Task::TYPE_HTML_VALIDATION,
                ],
            ],
        ];
    }
}
