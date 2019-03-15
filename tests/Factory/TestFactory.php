<?php

namespace App\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;
use webignition\NormalisedUrl\NormalisedUrl;

class TestFactory
{
    const KEY_TEST_ID = 'test-id';
    const KEY_USER = 'user';
    const KEY_WEBSITE = 'website';
    const KEY_STATE = 'state';
    const KEY_TASKS = 'tasks';
    const KEY_TASK_IDS = 'task-ids';
    const KEY_TYPE = 'type';
    const KEY_TASK_TYPES = 'task-types';

    const DEFAULT_USER = 'user@example.com';
    const DEFAULT_WEBSITE_URL = 'http://example.com/';
    const DEFAULT_STATE = Test::STATE_COMPLETED;
    const DEFAULT_TYPE = Test::TYPE_FULL_SITE;

    private $container;
    private $taskFactory;

    private $defaultTestValues = [
        self::KEY_USER => self::DEFAULT_USER,
        self::KEY_WEBSITE => self::DEFAULT_WEBSITE_URL,
        self::KEY_STATE => self::DEFAULT_STATE,
        self::KEY_TYPE => self::DEFAULT_TYPE,
    ];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->taskFactory = new TaskFactory($container);
    }

    public function create(array $testValues): Test
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get(EntityManagerInterface::class);

        foreach ($this->defaultTestValues as $key => $value) {
            if (!isset($testValues[$key])) {
                $testValues[$key] = $value;
            }
        }

        $testValues[self::KEY_WEBSITE] = new NormalisedUrl($testValues[self::KEY_WEBSITE]);

        $test = Test::create($testValues[self::KEY_TEST_ID]);

        $test->setWebsite($testValues[self::KEY_WEBSITE]);
        $test->setUser($testValues[self::KEY_USER]);
        $test->setState($testValues[self::KEY_STATE]);
        $test->setType($testValues[self::KEY_TYPE]);

        $entityManager->persist($test);
        $entityManager->flush();

        if (isset($testValues[self::KEY_TASKS])) {
            $this->taskFactory->createCollection($test, $testValues[self::KEY_TASKS]);
        }

        if (isset($testValues[self::KEY_TASK_IDS])) {
            $test->setTaskIdCollection($testValues[self::KEY_TASK_IDS]);
        }

        if (isset($testValues[self::KEY_TASK_TYPES])) {
            $test->setTaskTypes($testValues[self::KEY_TASK_TYPES]);
        }

        return $test;
    }
}
