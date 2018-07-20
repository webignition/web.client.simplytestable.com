<?php

namespace AppBundle\Services\Factory;

use AppBundle\Entity\Task\Task;
use AppBundle\Entity\Test\Test;

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

        if (array_key_exists('time_period', $taskData)) {
            $timePeriodData = $taskData['time_period'];
            if (array_key_exists('start_date_time', $timePeriodData)) {
                $task->getTimePeriod()->setStartDateTime(new \DateTime($timePeriodData['start_date_time']));
            }

            if (array_key_exists('end_date_time', $timePeriodData)) {
                $task->getTimePeriod()->setEndDateTime(new \DateTime($timePeriodData['end_date_time']));
            }
        }

        if (array_key_exists('output', $taskData)) {
            if (!$task->hasOutput()) {
                $output = $this->taskOutputFactory->create($taskData['type'], $taskData['output']);
                $task->setOutput($output);
            }
        }
    }
}
