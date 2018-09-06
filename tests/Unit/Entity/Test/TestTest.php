<?php

namespace App\Tests\Unit\Entity\Test;

use App\Entity\Task\Task;
use App\Entity\Test\Test;

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
                'test' => $this->createTest(Test::STATE_STARTING, []),
                'expectedCompletionPercent' => 0,
            ],
            'state: completed' => [
                'test' => $this->createTest(Test::STATE_COMPLETED, []),
                'expectedCompletionPercent' => 100,
            ],
            'task count zero' => [
                'test' => $this->createTest(Test::STATE_CANCELLED, []),
                'expectedCompletionPercent' => 100,
            ],
            'has tasks, none complete' => [
                'test' => $this->createTest(Test::STATE_IN_PROGRESS, [
                    $this->createTask(Task::STATE_IN_PROGRESS),
                    $this->createTask(Task::STATE_IN_PROGRESS),
                    $this->createTask(Task::STATE_IN_PROGRESS),
                ]),
                'expectedCompletionPercent' => 0,
            ],
            'has tasks, all complete' => [
                'test' => $this->createTest(Test::STATE_IN_PROGRESS, [
                    $this->createTask(Task::STATE_COMPLETED),
                ]),
                'expectedCompletionPercent' => 100,
            ],
            'has tasks, 25% complete' => [
                'test' => $this->createTest(Test::STATE_IN_PROGRESS, [
                    $this->createTask(Task::STATE_COMPLETED),
                    $this->createTask(Task::STATE_IN_PROGRESS),
                    $this->createTask(Task::STATE_QUEUED),
                    $this->createTask(Task::STATE_QUEUED_FOR_ASSIGNMENT),
                ]),
                'expectedCompletionPercent' => 25,
            ],
            'has tasks, 80% failed' => [
                'test' => $this->createTest(Test::STATE_IN_PROGRESS, [
                    $this->createTask(Task::STATE_FAILED),
                    $this->createTask(Task::STATE_FAILED_NO_RETRY_AVAILABLE),
                    $this->createTask(Task::STATE_FAILED_RETRY_AVAILABLE),
                    $this->createTask(Task::STATE_FAILED_RETRY_LIMIT_REACHED),
                    $this->createTask(Task::STATE_IN_PROGRESS),
                ]),
                'expectedCompletionPercent' => 80,
            ],
        ];
    }

    private function createTest($state, array $tasks): Test
    {
        $test = new Test();

        $test->setState($state);

        foreach ($tasks as $task) {
            $test->addTask($task);
        }

        return $test;
    }

    private function createTask($state): Task
    {
        $task = new Task();

        $task->setState($state);

        return $task;
    }
}
