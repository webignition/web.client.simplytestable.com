<?php

namespace App\Model\Test;

use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;

class DecoratedTest
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

    public function getErrorCount(): int
    {
        return $this->test->getErrorCount();
    }

    public function getWarningCount(): int
    {
        return $this->test->getWarningCount();
    }

    public function getErrorCountByTaskType(string $taskType): int
    {
        return $this->test->getErrorCountByTaskType($taskType);
    }

    public function getWarningCountByTaskType(string $taskType): int
    {
        return $this->test->getWarningCountByTaskType($taskType);
    }

    public function getWebsite(): string
    {
        return $this->test->getWebsite();
    }

    public function getErrorFreeTaskCount(): int
    {
        return $this->remoteTest->getErrorFreeTaskCount();
    }

    public function getTaskTypes(): array
    {
        return $this->remoteTest->getTaskTypes();
    }

    public function isFullSite(): bool
    {
        return $this->remoteTest->isFullSite();
    }

    public function isSingleUrl(): bool
    {
        return $this->remoteTest->isSingleUrl();
    }

    public function getTaskCount(): int
    {
        return $this->remoteTest->getTaskCount();
    }

    public function getParameter(string $key)
    {
        return $this->remoteTest->getParameter($key);
    }
}
