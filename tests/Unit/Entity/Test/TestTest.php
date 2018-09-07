<?php

namespace App\Tests\Unit\Entity\Test;

use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Tests\Factory\ModelFactory;

class TestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getCompletionPercentDataProvider
     *
     * @param Test $test
     * @param int $expectedCompletionPercent
     */
    public function testGetCompletionPercent(Test $test, int $expectedCompletionPercent)
    {
        $this->assertEquals($expectedCompletionPercent, $test->getCompletionPercent());
    }

    public function getCompletionPercentDataProvider(): array
    {
        return [
            'state: new' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                ]),
                'expectedCompletionPercent' => 0,
            ],
            'state: completed' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_COMPLETED,
                ]),
                'expectedCompletionPercent' => 100,
            ],
            'task count zero' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_CANCELLED,
                ]),
                'expectedCompletionPercent' => 100,
            ],
            'has tasks, none complete' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_IN_PROGRESS,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS]),
                    ],
                ]),
                'expectedCompletionPercent' => 0,
            ],
            'has tasks, all complete' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_IN_PROGRESS,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_COMPLETED]),
                    ],
                ]),
                'expectedCompletionPercent' => 100,
            ],
            'has tasks, 25% complete' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_IN_PROGRESS,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_COMPLETED]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_QUEUED]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_QUEUED_FOR_ASSIGNMENT]),
                    ],
                ]),
                'expectedCompletionPercent' => 25,
            ],
            'has tasks, 80% failed' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_IN_PROGRESS,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_FAILED]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_FAILED_NO_RETRY_AVAILABLE]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_FAILED_RETRY_AVAILABLE]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_FAILED_RETRY_LIMIT_REACHED]),
                        ModelFactory::createTask([ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS]),
                    ],
                ]),
                'expectedCompletionPercent' => 80,
            ],
        ];
    }

    /**
     * @dataProvider getTaskCountByStateDataProvider
     *
     * @param Test $test
     * @param string $state
     * @param int $expectedTaskCount
     */
    public function testGetTaskCountByState(Test $test, string $state, int $expectedTaskCount)
    {
        $this->assertEquals($expectedTaskCount, $test->getTaskCountByState($state));
    }

    public function getTaskCountByStateDataProvider(): array
    {
        return [
            'no tasks' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                ]),
                'state' => Task::STATE_COMPLETED,
                'expectedTaskCount' => 0,
            ],
            'non-excluded tasks only, state: completed' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_COMPLETED,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_COMPLETED,
                        ]),
                    ],
                ]),
                'state' => Task::STATE_COMPLETED,
                'expectedTaskCount' => 2,
            ],
            'non-excluded tasks only, state: in-progress' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                        ]),
                    ],
                ]),
                'state' => Task::STATE_IN_PROGRESS,
                'expectedTaskCount' => 3,
            ],
            'excluded tasks only, state: in-progress' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_IN_PROGRESS,
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                        ]),
                    ],
                ]),
                'state' => Task::STATE_IN_PROGRESS,
                'expectedTaskCount' => 0,
            ],
            'excluded and non-excluded tasks, state: cancelled' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_STATE => Test::STATE_STARTING,
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                        ]),
                        ModelFactory::createTask([
                            ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                            ModelFactory::TASK_TYPE => Task::TYPE_CSS_VALIDATION,
                        ]),
                    ],
                ]),
                'state' => Task::STATE_CANCELLED,
                'expectedTaskCount' => 2,
            ],
        ];
    }
}
