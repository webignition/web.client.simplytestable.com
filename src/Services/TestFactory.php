<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;

class TestFactory
{
    private $testCompletionPercentCalculator;

    public function __construct(TestCompletionPercentCalculator $remoteTestCompletionPercentCalculator)
    {
        $this->testCompletionPercentCalculator = $remoteTestCompletionPercentCalculator;
    }

    public function create(TestEntity $entity, RemoteTest $remoteTest, array $testData): TestModel
    {
        $state = $testData['state'] ?? '';
        $taskCount = $testData['task_count'] ?? 0;
        $taskCountByState = $this->calculateTaskCountByState($remoteTest);
        $crawlData = $testData['crawl'] ?? [];

        return new TestModel(
            $entity,
            $testData['website'] ?? '',
            $testData['user'] ?? '',
            $state,
            $testData['type'] ?? '',
            $this->normaliseTaskTypes($testData['task_types'] ?? []),
            $testData['url_count'] ?? 0,
            $entity->getErrorCount(),
            $entity->getWarningCount(),
            $taskCount,
            $testData['errored_task_count'] ?? 0,
            $testData['cancelled_task_count'] ?? 0,
            $testData['parameters'] ?? '',
            $testData['amendments'] ?? [],
            $this->testCompletionPercentCalculator->calculate(
                $state,
                $taskCount,
                $taskCountByState,
                $crawlData
            ),
            $taskCountByState,
            $crawlData,
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

    private function normaliseTaskTypes(array $taskTypes): array
    {
        $normalisedTaskTypes = [];

        foreach ($taskTypes as $taskType) {
            $taskTypeName = is_array($taskType) ? $taskType['name'] : $taskType;
            $normalisedTaskTypes[] = $taskTypeName;
        }

        return $normalisedTaskTypes;
    }
}
