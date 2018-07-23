<?php

namespace App\Tests\Unit\Entity\Task;

use App\Entity\Task\Task;

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
}
