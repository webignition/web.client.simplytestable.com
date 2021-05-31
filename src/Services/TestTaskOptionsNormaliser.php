<?php

namespace App\Services;

class TestTaskOptionsNormaliser
{
    public function normalise(array $taskTypes, array $taskTypeOptionsCollection): array
    {
        $taskOptions = [];

        foreach ($taskTypes as $taskType) {
            $taskTypeKey = $this->createTaskTypeKey($taskType);
            $taskOptions[$taskTypeKey] = 1;
        }

        foreach ($taskTypeOptionsCollection as $taskType => $taskTypeOptions) {
            $taskTypeKey = $this->createTaskTypeKey($taskType);

            foreach ($taskTypeOptions as $taskTypeOptionKey => $taskTypeOptionValue) {
                $taskOptions[$taskTypeKey . '-' . $taskTypeOptionKey] = $taskTypeOptionValue;
            }
        }

        return $taskOptions;
    }

    private function createTaskTypeKey(string $taskType): string
    {
        return strtolower(str_replace(' ', '-', $taskType));
    }
}
