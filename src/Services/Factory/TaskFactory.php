<?php

namespace App\Services\Factory;

use App\Entity\Task\Task;
use App\Entity\Test;
use App\Entity\TimePeriod;

class TaskFactory
{
    /**
     * @var TaskOutputFactory
     */
    private $taskOutputFactory;

    /**
     * @param TaskOutputFactory $taskOutputFactory
     */
    public function __construct(TaskOutputFactory $taskOutputFactory)
    {
        $this->taskOutputFactory = $taskOutputFactory;
    }

    /**
     * @param Test $test
     *
     * @return Task
     */
    public function create(Test $test)
    {
        $task = new Task();
        $task->setTest($test);

        return $task;
    }

    /**
     * @param Task $task
     * @param array $taskData
     */
    public function hydrate(Task $task, array $taskData)
    {
        $task->setTaskId($taskData['id']);
        $task->setUrl($taskData['url']);
        $task->setState($taskData['state']);
        $task->setWorker($taskData['worker']);
        $task->setType($taskData['type']);

        $timePeriodData = $taskData['time_period'] ?? null;

        if ($timePeriodData) {
            $startDateTimeValue = $timePeriodData['start_date_time'] ?? null;
            $endDateTimeValue = $timePeriodData['end_date_time'] ?? null;

            $timePeriod = TimePeriod::create(
                $startDateTimeValue ? new \DateTime($startDateTimeValue) : null,
                $endDateTimeValue ? new \DateTime($endDateTimeValue) : null
            );

            $task->setTimePeriod($timePeriod);
        }

        if (array_key_exists('output', $taskData)) {
            if (!$task->hasOutput()) {
                $output = $this->taskOutputFactory->create($taskData['type'], $taskData['output']);
                $task->setOutput($output);
            }
        }
    }
}
