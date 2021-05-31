<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;

class TestFactory
{
    private $testCompletionPercentCalculator;
    private $testTaskCountByStateNormaliser;
    private $testTaskOptionsNormaliser;

    public function __construct(
        TestCompletionPercentCalculator $remoteTestCompletionPercentCalculator,
        TestTaskCountByStateNormaliser $testTaskCountByStateNormaliser,
        TestTaskOptionsNormaliser $testTaskOptionsNormaliser
    ) {
        $this->testCompletionPercentCalculator = $remoteTestCompletionPercentCalculator;
        $this->testTaskCountByStateNormaliser = $testTaskCountByStateNormaliser;
        $this->testTaskOptionsNormaliser = $testTaskOptionsNormaliser;
    }

    public function create(TestEntity $entity, array $testData): TestModel
    {
        if (array_key_exists('ammendments', $testData)) {
            $testData['amendments'] = $testData['ammendments'];
        }

        $taskCount = $testData['task_count'] ?? 0;
        $taskCountByState = $this->testTaskCountByStateNormaliser->normalise($testData['task_count_by_state'] ?? []);
        $crawlData = $testData['crawl'] ?? [];
        $state = $this->normaliseState($testData['state'] ?? '', $crawlData);
        $taskTypes = $this->normaliseTaskTypes($testData['task_types'] ?? []);
        $taskTypeOptions = $testData['task_type_options'] ?? [];
        $taskOptions = $this->testTaskOptionsNormaliser->normalise($taskTypes, $taskTypeOptions);

        $timePeriodData = $testData['time_period'] ?? [];

        return new TestModel(
            $entity,
            $testData['website'] ?? '',
            $testData['user'] ?? '',
            $state,
            $testData['type'] ?? '',
            $taskTypes,
            $testData['url_count'] ?? 0,
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
            $testData['rejection'] ?? [],
            $testData['is_public'] ?? false,
            $taskOptions,
            $testData['owners'] ?? [],
            $this->createDateTimeFromString($timePeriodData['start_date_time'] ?? null),
            $this->createDateTimeFromString($timePeriodData['end_date_time'] ?? null)
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

    private function normaliseState(string $state, array $crawlData): string
    {
        if (TestModel::STATE_FAILED_NO_SITEMAP === $state && !empty($crawlData)) {
            return TestModel::STATE_CRAWLING;
        }

        return $state;
    }

    private function createDateTimeFromString(?string $datetime = null): ?\DateTime
    {
        if (null === $datetime) {
            return null;
        }

        try {
            return new \DateTime($datetime);
        } catch (\Exception $exception) {
        }

        return null;
    }
}
