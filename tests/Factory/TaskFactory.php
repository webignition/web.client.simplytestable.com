<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Factory;

use App\Entity\Task\Output;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Task;
use App\Entity\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskFactory
{
    const KEY_TASK_ID = 'task-id';
    const KEY_URL = 'url';
    const KEY_STATE = 'state';
    const KEY_TYPE = 'type';
    const KEY_TEST = 'test';
    const KEY_OUTPUT  = 'output';

    const DEFAULT_URL = 'http://example.com/';
    const DEFAULT_STATE = Task::STATE_COMPLETED;
    const DEFAULT_TYPE = Task::TYPE_HTML_VALIDATION;

    private $container;

    private $defaultTaskValues = [
        self::KEY_URL => self::DEFAULT_URL,
        self::KEY_STATE => self::DEFAULT_STATE,
        self::KEY_TYPE => self::DEFAULT_TYPE,
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(array $taskValues): Task
    {
        $outputFactory = new OutputFactory($this->container);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);

        foreach ($this->defaultTaskValues as $key => $value) {
            if (!isset($taskValues[$key])) {
                $taskValues[$key] = $value;
            }
        }

        $task = new Task();

        $task->setTaskId($taskValues[self::KEY_TASK_ID]);
        $task->setUrl($taskValues[self::KEY_URL]);
        $task->setState($taskValues[self::KEY_STATE]);
        $task->setType($taskValues[self::KEY_TYPE]);
        $task->setTest($taskValues[self::KEY_TEST]);

        if (isset($taskValues[self::KEY_OUTPUT])) {
            $outputValue = $taskValues[self::KEY_OUTPUT];

            if (is_array($outputValue)) {
                $outputValue = $outputFactory->create($outputValue);
            }

            $task->setOutput($outputValue);
        }

        $entityManager->persist($task);
        $entityManager->flush();

        return $task;
    }

    /**
     * @return Task[]
     */
    public function createCollection(Test $test, array $taskValuesCollection): array
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $tasks = [];

        foreach ($taskValuesCollection as $taskValues) {
            $taskValues[self::KEY_TEST] = $test;

            $task = $this->create($taskValues);
            $test->addTask($task);

            $entityManager->persist($test);
            $entityManager->flush();

            $tasks[] = $task;
        }

        return $tasks;
    }
}
