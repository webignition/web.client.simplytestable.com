<?php

namespace App\Model;

use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

class DecoratedTest implements \JsonSerializable, TestInterface
{
    private $test;

    public function __construct(Test $test)
    {
        $this->test = $test;
    }

    public function getEntity(): TestEntity
    {
        return $this->test->getEntity();
    }

    public function getTestId(): int
    {
        return $this->test->getTestId();
    }

    /**
     * @return int[]
     */
    public function getTaskIds(): array
    {
        return $this->test->getTaskIds();
    }

    public function getWebsite(): string
    {
        return $this->test->getWebsite();
    }

    public function getUser(): string
    {
        return $this->test->getUser();
    }

    public function getState(): string
    {
        return $this->test->getState();
    }

    public function getType(): string
    {
        return $this->test->getType();
    }

    /**
     * @return string[]
     */
    public function getTaskTypes(): array
    {
        return $this->test->getTaskTypes();
    }

    public function getUrlCount(): int
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

    /**
     * @return DoctrineCollection|Task[]
     */
    public function getTasks(): DoctrineCollection
    {
        return $this->test->getTasks();
    }

    public function getLocalTaskCount(): int
    {
        return $this->test->getLocalTaskCount();
    }

    public function getRemoteTaskCount(): int
    {
        return $this->test->getRemoteTaskCount();
    }

    public function getTasksWithErrorsCount(): int
    {
        return $this->test->getTasksWithErrorsCount();
    }

    public function getCancelledTaskCount(): int
    {
        return $this->test->getCancelledTaskCount();
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

    public function getAmendments(): array
    {
        return $this->test->getAmendments();
    }

    public function getCompletionPercent(): int
    {
        return $this->test->getCompletionPercent();
    }

    public function getTaskCountByState(): array
    {
        return $this->test->getTaskCountByState();
    }

    public function getRejection(): array
    {
        return $this->test->getRejection();
    }

    public function requiresRemoteTasks(): bool
    {
        return $this->test->requiresRemoteTasks();
    }

    public function getCrawlData(): array
    {
        return $this->test->getCrawlData();
    }

    public function isPublic(): bool
    {
        return $this->test->isPublic();
    }

    public function getTaskOptions(): array
    {
        return $this->test->getTaskOptions();
    }

    /**
     * @return string[]
     */
    public function getOwners(): array
    {
        return $this->test->getOwners();
    }

    public function isFinished(): bool
    {
        return $this->test->isFinished();
    }

    public function getHash(): string
    {
        return $this->test->getHash();
    }

    public function isFullSite(): bool
    {
        return TestEntity::TYPE_FULL_SITE === $this->test->getType();
    }

    public function isSingleUrl(): bool
    {
        return TestEntity::TYPE_SINGLE_URL === $this->test->getType();
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
}
