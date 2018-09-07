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

    /**
     * @dataProvider getErrorCountDataProvider
     *
     * @param Test $test
     * @param int $expectedErrorCount
     */
    public function testGetErrorCount(Test $test, int $expectedErrorCount)
    {
        $this->assertEquals($expectedErrorCount, $test->getErrorCount());
    }

    public function getErrorCountDataProvider(): array
    {
        return [
            'no tasks' => [
                'test' => ModelFactory::createTest(),
                'expectedErrorCount' => 0,
            ],
            'has tasks, no output' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask(),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, no errors' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 0,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, has errors' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 3,
            ],
            'has tasks, has output, has errors, is excluded' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
        ];
    }

    /**
     * @dataProvider getWarningCountDataProvider
     *
     * @param Test $test
     * @param int $expectedWarningCount
     */
    public function testGetWarningCount(Test $test, int $expectedWarningCount)
    {
        $this->assertEquals($expectedWarningCount, $test->getWarningCount());
    }

    public function getWarningCountDataProvider(): array
    {
        return [
            'no tasks' => [
                'test' => ModelFactory::createTest(),
                'expectedErrorCount' => 0,
            ],
            'has tasks, no output' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask(),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, no warnings' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 0,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, has warnings' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 3,
            ],
            'has tasks, has output, has warnings, is excluded' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'expectedErrorCount' => 0,
            ],
        ];
    }

    /**
     * @dataProvider getErrorCountByTaskTypeDataProvider
     *
     * @param Test $test
     * @param string $taskType
     * @param int $expectedErrorCount
     */
    public function testGetErrorCountByTaskType(Test $test, string $taskType, int $expectedErrorCount)
    {
        $this->assertEquals($expectedErrorCount, $test->getErrorCountByTaskType($taskType));
    }

    public function getErrorCountByTaskTypeDataProvider(): array
    {
        return [
            'no tasks' => [
                'test' => ModelFactory::createTest(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedErrorCount' => 0,
            ],
            'has tasks, no output' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask(),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, no errors' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 0,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, has errors, no errors for selected type' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'expectedErrorCount' => 0,
            ],
            'has tasks, has output, has errors, has errors for selected type' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedErrorCount' => 3,
            ],
            'has tasks, has output, has errors, is excluded' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_ERROR_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_JS_STATIC_ANALYSIS,
                'expectedErrorCount' => 0,
            ],
        ];
    }

    /**
     * @dataProvider getWarningCountByTaskTypeDataProvider
     *
     * @param Test $test
     * @param string $taskType
     * @param int $expectedWarningCount
     */
    public function testGetWarningCountByTaskType(Test $test, string $taskType, int $expectedWarningCount)
    {
        $this->assertEquals($expectedWarningCount, $test->getWarningCountByTaskType($taskType));
    }

    public function getWarningCountByTaskTypeDataProvider(): array
    {
        return [
            'no tasks' => [
                'test' => ModelFactory::createTest(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedWarningCount' => 0,
            ],
            'has tasks, no output' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask(),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedWarningCount' => 0,
            ],
            'has tasks, has output, no warnings' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 0,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedWarningCount' => 0,
            ],
            'has tasks, has output, has warnings, no warnings for selected type' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'expectedWarningCount' => 0,
            ],
            'has tasks, has output, has warnings, has warnings for selected type' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'expectedWarningCount' => 3,
            ],
            'has tasks, has output, has warnings, is excluded' => [
                'test' => ModelFactory::createTest([
                    ModelFactory::TEST_TASKS => [
                        ModelFactory::createTask([
                            ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                            ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                                ModelFactory::TASK_OUTPUT_WARNING_COUNT => 3,
                                ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                            ]),
                        ]),
                    ],
                ]),
                'taskType' => Task::TYPE_JS_STATIC_ANALYSIS,
                'expectedWarningCount' => 0,
            ],
        ];
    }
}
