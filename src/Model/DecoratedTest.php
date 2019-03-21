<?php

namespace App\Model;

use App\Entity\Test as TestEntity;

class DecoratedTest implements \JsonSerializable
{
    private $test;

    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    public function getTestId(): int
    {
        return $this->test->getTestId();
    }

    public function getWebsite(): string
    {
        return $this->test->getWebsite();
    }

    public function getState(): string
    {
        return $this->test->getState();
    }

    public function isFullSite(): bool
    {
        return TestEntity::TYPE_FULL_SITE === $this->test->getType();
    }

    public function isSingleUrl(): bool
    {
        return TestEntity::TYPE_SINGLE_URL === $this->test->getType();
    }

    public function getTaskTypes(): array
    {
        return $this->test->getTaskTypes();
    }

    public function getUrlCount(): ?int
    {
        return $this->test->getUrlCount();
    }

    public function getErrorCount(): int
    {
        return $this->test->getErrorCount();
    }

    public function getWarningCount(): int
    {
        return $this->test->getWarningCount();
    }

    public function getErrorCountByTaskType(string $type): int
    {
        $tasks = $this->test->getTasks();
        $count = 0;

        foreach ($tasks as $task) {
            if ($task->hasOutput() && $task->getType() === $type) {
                $count += $task->getOutput()->getErrorCount();
            }
        }

        return $count;
    }

    public function getWarningCountByTaskType(string $type): int
    {
        $tasks = $this->test->getTasks();
        $count = 0;

        foreach ($tasks as $task) {
            if ($task->hasOutput() && $task->getType() === $type) {
                $count += $task->getOutput()->getWarningCount();
            }
        }

        return $count;
    }

    public function getErrorFreeTaskCount(): int
    {
        $remoteTaskCount = $this->test->getRemoteTaskCount();
        $tasksWithErrorsCount = $this->test->getTasksWithErrorsCount();
        $cancelledTaskCount = $this->test->getCancelledTaskCount();

        return $remoteTaskCount - $tasksWithErrorsCount - $cancelledTaskCount;
    }

    public function getLocalTaskCount(): int
    {
        return $this->test->getLocalTaskCount();
    }

    public function getRemoteTaskCount(): int
    {
        return $this->test->getRemoteTaskCount();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key)
    {
        return $this->test->getParameter($key);
    }

    public function getAmendments()
    {
        return $this->test->getAmendments();
    }

    public function getCompletionPercent()
    {
        return $this->test->getCompletionPercent();
    }

    public function getTaskCountByState()
    {
        return $this->test->getTaskCountByState();
    }

    public function getRejection(): array
    {
        return $this->test->getRejection();
    }

    public function requiresRemoteTasks(): bool
    {
        return $this->getRemoteTaskCount() > $this->getLocalTaskCount();
    }

    public function getEntity(): TestEntity
    {
        return $this->test->getEntity();
    }

    public function getCrawlData(): array
    {
        return $this->test->getCrawlData();
    }

    public function getFormattedWebsite(): string
    {
        return rawurldecode((string) $this->getWebsite());
    }

    public function jsonSerialize(): array
    {
        return [
            'user' => $this->test->getUser(),
            'website' => $this->getWebsite(),
            'state' => $this->getState(),
            'taskTypes' => $this->getTaskTypes(),
            'task_count' => $this->getRemoteTaskCount(),
            'completion_percent' => $this->getCompletionPercent(),
            'task_count_by_state' => $this->getTaskCountByState(),
            'amendments' => $this->getAmendments(),
        ];
    }

    public function getHash(): string
    {
        return md5(json_encode([
            'test_id' => $this->getTestId(),
            'state' => $this->getState(),
            'requires_remote_tasks' => $this->requiresRemoteTasks(),
        ]));
    }
}
