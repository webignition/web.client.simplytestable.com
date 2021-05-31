<?php

namespace App\Services;

class TestTaskCountByStateNormaliser
{
    private $stateNameMap = [
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

    public function normalise(array $taskCountByState): array
    {
        $normalisedTaskCountByState = [];

        foreach ($this->stateNameMap as $stateName => $normalisedStateName) {
            if (!isset($normalisedTaskCountByState[$normalisedStateName])) {
                $normalisedTaskCountByState[$normalisedStateName] = 0;
            }

            $normalisedTaskCountByState[$normalisedStateName] += $taskCountByState[$stateName] ?? 0;
        }

        return $normalisedTaskCountByState;
    }
}
