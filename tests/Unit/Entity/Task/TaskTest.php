<?php

namespace App\Tests\Unit\Entity\Task;

use App\Entity\Task\Task;
use App\Tests\Factory\ModelFactory;

class TaskTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getStateLabelDataProvider
     *
     * @param string $state
     * @param bool $expectedStateLabel
     */
    public function testGetStateLabel($state, $expectedStateLabel)
    {
        $task = new Task();
        $task->setState($state);

        $this->assertEquals($expectedStateLabel, $task->getStateLabel());
    }

    /**
     * @return array
     */
    public function getStateLabelDataProvider()
    {
        return [
            Task::STATE_CANCELLED => [
                'state' => Task::STATE_CANCELLED,
                'expectedStateLabel' => Task::STATE_CANCELLED,
            ],
            Task::STATE_QUEUED => [
                'state' => Task::STATE_QUEUED,
                'expectedStateLabel' => Task::STATE_QUEUED,
            ],
            Task::STATE_IN_PROGRESS => [
                'state' => Task::STATE_IN_PROGRESS,
                'expectedStateLabel' => Task::STATE_IN_PROGRESS,
            ],
            Task::STATE_COMPLETED => [
                'state' => Task::STATE_COMPLETED,
                'expectedStateLabel' => Task::STATE_COMPLETED,
            ],
            Task::STATE_AWAITING_CANCELLATION => [
                'state' => Task::STATE_AWAITING_CANCELLATION,
                'expectedStateLabel' => Task::STATE_AWAITING_CANCELLATION,
            ],
            Task::STATE_QUEUED_FOR_ASSIGNMENT => [
                'state' => Task::STATE_QUEUED_FOR_ASSIGNMENT,
                'expectedStateLabel' => Task::STATE_QUEUED,
            ],
            Task::STATE_SKIPPED => [
                'state' => Task::STATE_SKIPPED,
                'expectedStateLabel' => Task::STATE_SKIPPED,
            ],
            Task::STATE_FAILED_NO_RETRY_AVAILABLE => [
                'state' => Task::STATE_FAILED_NO_RETRY_AVAILABLE,
                'expectedStateLabel' => Task::STATE_FAILED,
            ],
            Task::STATE_FAILED_RETRY_AVAILABLE => [
                'state' => Task::STATE_FAILED_RETRY_AVAILABLE,
                'expectedStateLabel' => Task::STATE_FAILED,
            ],
            Task::STATE_FAILED_RETRY_LIMIT_REACHED => [
                'state' => Task::STATE_FAILED_RETRY_LIMIT_REACHED,
                'expectedStateLabel' => Task::STATE_FAILED,
            ],
            Task::STATE_FAILED => [
                'state' => Task::STATE_FAILED,
                'expectedStateLabel' => Task::STATE_FAILED,
            ],
        ];
    }

    /**
     * @dataProvider hasOutputDataProvider
     *
     * @param Task $task
     * @param bool $expectedHasOutput
     */
    public function testHasOutput(Task $task, bool $expectedHasOutput)
    {
        $this->assertEquals($expectedHasOutput, $task->hasOutput());
    }

    public function hasOutputDataProvider(): array
    {
        return [
            'no output' => [
                'task' => ModelFactory::createTask(),
                'expectedHasOutput' => false,
            ],
            'has output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_TYPE => Task::TYPE_HTML_VALIDATION,
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput(),
                ]),
                'expectedHasOutput' => true,
            ],
            'has output, has excluded type' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput(),
                ]),
                'expectedHasOutput' => false,
            ],
        ];
    }
}
