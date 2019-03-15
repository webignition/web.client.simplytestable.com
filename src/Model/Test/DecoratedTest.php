<?php

namespace App\Model\Test;

use App\Entity\Test as TestEntity;
use App\Model\Test;
use App\Model\RemoteTest\RemoteTest;

class DecoratedTest implements \JsonSerializable
{
    private $test;
    private $remoteTest;

    public function __construct(Test $test, RemoteTest $remoteTest)
    {
        $this->test = $test;
        $this->remoteTest = $remoteTest;
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

//    public function getErrorCount(): int
//    {
//        return $this->test->getErrorCount();
//    }
//
//    public function getWarningCount(): int
//    {
//        return $this->test->getWarningCount();
//    }
//
//    public function getErrorCountByTaskType(string $taskType): int
//    {
//        return $this->test->getErrorCountByTaskType($taskType);
//    }
//
//    public function getWarningCountByTaskType(string $taskType): int
//    {
//        return $this->test->getWarningCountByTaskType($taskType);
//    }
//
//
//    public function getErrorFreeTaskCount(): int
//    {
//        return $this->remoteTest->getErrorFreeTaskCount();
//    }
//
//    public function getTaskCount(): int
//    {
//        return $this->remoteTest->getTaskCount();
//    }
//
//    public function getParameter(string $key)
//    {
//        return $this->remoteTest->getParameter($key);
//    }
//
//    public function getAmendments()
//    {
//        $amendments = $this->remoteTest->getAmmendments();
//
//        return $amendments ?? [];
//    }
//
//    public function getCompletionPercent()
//    {
//        return $this->remoteTest->getCompletionPercent();
//    }
//
//    public function getTaskCountByState()
//    {
//        return $this->remoteTest->getTaskCountByState();
//    }
//
//    public function getRejection()
//    {
//        return $this->remoteTest->getRejection();
//    }
//
//    public function requiresRemoteTasks(): bool
//    {
//        return $this->remoteTest->getTaskCount() !== $this->test->getTaskCount();
//    }
//
//    public function getTest(): Test
//    {
//        return $this->test;
//    }
//
//    public function getCrawlData(): array
//    {
//        return $this->remoteTest->getCrawl();
//    }
//
//    public function getFormattedWebsite(): string
//    {
//        return $this->test->getFormattedWebsite();
//    }
//
    public function jsonSerialize(): array
    {
        return [];

//        return array_merge($this->test->jsonSerialize(), [
//            'task_count' => $this->getTaskCount(),
//            'completion_percent' => $this->getCompletionPercent(),
//            'task_count_by_state' => $this->getTaskCountByState(),
//            'amendments' => $this->getAmendments(),
//        ]);
    }
//
//    public function __toArray(): array
//    {
//        return array_merge($this->test->jsonSerialize(), $this->remoteTest->__toArray());
//    }
}
