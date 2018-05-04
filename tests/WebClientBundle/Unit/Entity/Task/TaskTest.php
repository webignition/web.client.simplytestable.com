<?php

namespace Tests\WebClientBundle\Unit\Entity\Task;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider hasFailedDataProvider
     *
     * @param string $state
     * @param bool $expectedHasFailed
     */
    public function testHasFailed($state, $expectedHasFailed)
    {
        $task = new Task();
        $task->setState($state);

        $this->assertEquals($expectedHasFailed, $task->hasFailed());
    }

    /**
     * @return array
     */
    public function hasFailedDataProvider()
    {
        return [
            Task::STATE_CANCELLED => [
                'state' => Task::STATE_CANCELLED,
                'expectedHasFailed' => false,
            ],
            Task::STATE_QUEUED => [
                'state' => Task::STATE_QUEUED,
                'expectedHasFailed' => false,
            ],
            Task::STATE_IN_PROGRESS => [
                'state' => Task::STATE_IN_PROGRESS,
                'expectedHasFailed' => false,
            ],
            Task::STATE_COMPLETED => [
                'state' => Task::STATE_COMPLETED,
                'expectedHasFailed' => false,
            ],
            Task::STATE_AWAITING_CANCELLATION => [
                'state' => Task::STATE_AWAITING_CANCELLATION,
                'expectedHasFailed' => false,
            ],
            Task::STATE_QUEUED_FOR_ASSIGNMENT => [
                'state' => Task::STATE_QUEUED_FOR_ASSIGNMENT,
                'expectedHasFailed' => false,
            ],
            Task::STATE_SKIPPED => [
                'state' => Task::STATE_SKIPPED,
                'expectedHasFailed' => false,
            ],
            Task::STATE_FAILED_NO_RETRY_AVAILABLE => [
                'state' => Task::STATE_FAILED_NO_RETRY_AVAILABLE,
                'expectedHasFailed' => true,
            ],
            Task::STATE_FAILED_RETRY_AVAILABLE => [
                'state' => Task::STATE_FAILED_RETRY_AVAILABLE,
                'expectedHasFailed' => true,
            ],
            Task::STATE_FAILED_RETRY_LIMIT_REACHED => [
                'state' => Task::STATE_FAILED_RETRY_LIMIT_REACHED,
                'expectedHasFailed' => true,
            ],
            Task::STATE_FAILED => [
                'state' => Task::STATE_FAILED,
                'expectedHasFailed' => true,
            ],
        ];
    }
}
