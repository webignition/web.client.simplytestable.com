<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskFactory
{
    const KEY_TASK_ID = 'task-id';
    const KEY_URL = 'url';
    const KEY_STATE = 'state';
    const KEY_TYPE = 'type';
    const KEY_TEST = 'test';

    const DEFAULT_URL = 'http://example.com/';
    const DEFAULT_STATE = Task::STATE_COMPLETED;
    const DEFAULT_TYPE = Task::TYPE_HTML_VALIDATION;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $defaultTaskValues = [
        self::KEY_URL => self::DEFAULT_URL,
        self::KEY_STATE => self::DEFAULT_STATE,
        self::KEY_TYPE => self::DEFAULT_TYPE,
    ];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $taskValues
     *
     * @return Task
     */
    public function create(array $taskValues)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

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

        $entityManager->persist($task);
        $entityManager->flush();

        return $task;
    }

    /**
     * @param Test $test
     * @param array $taskValuesCollection
     *
     * @return Task[]
     */
    public function createCollection(Test $test, array $taskValuesCollection)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

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
