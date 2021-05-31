<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Task;

class TaskServiceIsIncompleteTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider isIncompleteDataProvider
     */
    public function testIsIncomplete(Task $task, bool $expectedIsIncomplete)
    {
        $this->assertEquals($expectedIsIncomplete, $this->taskService->isIncomplete($task));
    }

    public function isIncompleteDataProvider(): array
    {
        return [
            'cancelled' => [
                'task' => $this->createTaskWithState(Task::STATE_CANCELLED),
                'expectedIsIncomplete' => false,
            ],
            'queued' => [
                'task' => $this->createTaskWithState(Task::STATE_QUEUED),
                'expectedIsIncomplete' => true,
            ],
            'in-progress' => [
                'task' => $this->createTaskWithState(Task::STATE_IN_PROGRESS),
                'expectedIsIncomplete' => true,
            ],
            'completed' => [
                'task' => $this->createTaskWithState(Task::STATE_COMPLETED),
                'expectedIsIncomplete' => false,
            ],
            'awaiting-cancellation' => [
                'task' => $this->createTaskWithState(Task::STATE_AWAITING_CANCELLATION),
                'expectedIsIncomplete' => false,
            ],
            'queued-for-assignment' => [
                'task' => $this->createTaskWithState(Task::STATE_QUEUED),
                'expectedIsIncomplete' => true,
            ],
            'failed-no-retry-available' => [
                'task' => $this->createTaskWithState(Task::STATE_FAILED_NO_RETRY_AVAILABLE),
                'expectedIsIncomplete' => false,
            ],
            'failed-retry-available' => [
                'task' => $this->createTaskWithState(Task::STATE_FAILED_RETRY_AVAILABLE),
                'expectedIsIncomplete' => false,
            ],
            'failed-retry-limit-reached' => [
                'task' => $this->createTaskWithState(Task::STATE_FAILED_RETRY_LIMIT_REACHED),
                'expectedIsIncomplete' => false,
            ],
            'skipped' => [
                'task' => $this->createTaskWithState(Task::STATE_SKIPPED),
                'expectedIsIncomplete' => false,
            ],
            'failed' => [
                'task' => $this->createTaskWithState(Task::STATE_FAILED),
                'expectedIsIncomplete' => false,
            ],
        ];
    }

    /**
     * @param string $state
     *
     * @return Task
     */
    private function createTaskWithState($state)
    {
        $task = new Task();
        $task->setState($state);

        return $task;
    }
}
