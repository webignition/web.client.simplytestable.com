<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;

class TestFactory
{
    private $remoteTestCompletionPercentCalculator;

    public function __construct(RemoteTestCompletionPercentCalculator $remoteTestCompletionPercentCalculator)
    {
        $this->remoteTestCompletionPercentCalculator = $remoteTestCompletionPercentCalculator;
    }

    public function create(TestEntity $entity, RemoteTest $remoteTest, array $testData): TestModel
    {
        return new TestModel(
            $entity,
            $testData['website'] ?? '',
            $testData['user'] ?? '',
            $testData['state'] ?? '',
            $testData['type'] ?? '',
            $remoteTest->getTaskTypes(),
            $testData['url_count'] ?? 0,
            $entity->getErrorCount(),
            $entity->getWarningCount(),
            $testData['task_count'] ?? 0,
            $testData['errored_task_count'] ?? 0,
            $testData['cancelled_task_count'] ?? 0,
            $testData['parameters'] ?? '',
            $testData['amendments'] ?? [],
            $this->remoteTestCompletionPercentCalculator->calculate($remoteTest->getCrawl(), $remoteTest),
            $this->calculateTaskCountByState($remoteTest),
            $testData['crawl'] ?? [],
            $remoteTest->getRejection()
        );
    }

    private function calculateTaskCountByState(RemoteTest $remoteTest): array
    {
        $rawTaskCountByState = $remoteTest->getRawTaskCountByState();

        $taskStates = [
            'in-progress' => 'in_progress',
            'queued' => 'queued',
            'queued-for-assignment' => 'queued',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'awaiting-cancellation' => 'cancelled',
            'failed' => 'failed',
            'failed-no-retry-available' => 'failed',
            'failed-retry-available' => 'failed',
            'failed-retry-limit-reached' => 'failed',
            'skipped' => 'skipped'
        ];

        $taskCountByState = [];

        foreach ($taskStates as $taskState => $translatedState) {
            if (!isset($taskCountByState[$translatedState])) {
                $taskCountByState[$translatedState] = 0;
            }

            $taskCountByState[$translatedState] += $rawTaskCountByState[$taskState] ?? 0;
        }

        return $taskCountByState;
    }
}
