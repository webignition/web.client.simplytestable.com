<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test as TestModel;

class TestFactory
{
    private $testCompletionPercentCalculator;
    private $testTaskCountByStateNormaliser;

    public function __construct(
        TestCompletionPercentCalculator $remoteTestCompletionPercentCalculator,
        TestTaskCountByStateNormaliser $testTaskCountByStateNormaliser
    ) {
        $this->testCompletionPercentCalculator = $remoteTestCompletionPercentCalculator;
        $this->testTaskCountByStateNormaliser = $testTaskCountByStateNormaliser;
    }

    public function create(TestEntity $entity, RemoteTest $remoteTest, array $testData): TestModel
    {
        $state = $testData['state'] ?? '';
        $taskCount = $testData['task_count'] ?? 0;
        $taskCountByState = $this->testTaskCountByStateNormaliser->normalise($testData['task_count_by_state'] ?? []);
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
