<?php

namespace App\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestFactory
{
    const KEY_TEST_ID = 'test-id';
    const KEY_TASKS = 'tasks';
    const KEY_TASK_IDS = 'task-ids';

    const DEFAULT_USER = 'user@example.com';
    const DEFAULT_WEBSITE_URL = 'http://example.com/';
    const DEFAULT_STATE = Test::STATE_COMPLETED;
    const DEFAULT_TYPE = Test::TYPE_FULL_SITE;

    private $container;
    private $taskFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->taskFactory = new TaskFactory($container);
    }

    public function create(array $testValues): Test
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);

        $test = Test::create($testValues[self::KEY_TEST_ID]);
        $entityManager->persist($test);
        $entityManager->flush();

        if (isset($testValues[self::KEY_TASKS])) {
            $this->taskFactory->createCollection($test, $testValues[self::KEY_TASKS]);
        }

        if (isset($testValues[self::KEY_TASK_IDS])) {
            $test->setTaskIdCollection($testValues[self::KEY_TASK_IDS]);
        }

        return $test;
    }
}
