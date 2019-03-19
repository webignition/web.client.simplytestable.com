<?php

namespace App\Services;

use App\Entity\Test as TestEntity;

class TestCompletionPercentCalculator
{
    /**
     * @var string[]
     */
    private $taskFinishedStates = [
        'cancelled',
        'completed',
        'failed',
        'skipped'
    ];

    public function calculate(string $state, int $taskCount, array $taskCountByState, array $crawlData): int
    {
        if (TestEntity::STATE_CRAWLING === $state && !empty($crawlData)) {
            return $this->calculateForCrawl($crawlData);
        }

        if (0 === $taskCount) {
            return 0;
        }

        $finishedCount = $this->getFinishedTaskCount($taskCountByState);
        if ($finishedCount === $taskCount) {
            return 100;
        }

        return (int) floor(($finishedCount / $taskCount) * 100);
    }

    private function calculateForCrawl(array $crawlData): int
    {
        $discoveredUrlCount = $crawlData['discovered_url_count'];
        if (0 === $discoveredUrlCount) {
            return 0;
        }

        return (int) round(($discoveredUrlCount / $crawlData['limit']) * 100);
    }

    private function getFinishedTaskCount(array $taskCountByState): int
    {
        $finishedCount = 0;

        foreach ($taskCountByState as $stateName => $taskCount) {
            if (in_array($stateName, $this->taskFinishedStates)) {
                $finishedCount += $taskCount;
            }
        }

        return $finishedCount;
    }
}
