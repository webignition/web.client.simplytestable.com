<?php

namespace App\Model\Test;

use App\Entity\Test\Test;

class DecoratedTest
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
}
