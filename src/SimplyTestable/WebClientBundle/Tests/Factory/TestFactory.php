<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Symfony\Component\DependencyInjection\ContainerInterface;
use webignition\NormalisedUrl\NormalisedUrl;

class TestFactory
{
    const KEY_TEST_ID = 'test-id';
    const KEY_USER = 'user';
    const KEY_WEBSITE = 'website';
    const KEY_STATE = 'state';
    const KEY_TASKS = 'tasks';

    const DEFAULT_USER = 'user@example.com';
    const DEFAULT_WEBSITE_URL = 'http://example.com/';
    const DEFAULT_STATE = Test::STATE_COMPLETED;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TaskFactory
     */
    private $taskFactory;

    /**
     * @var array
     */
    private $defaultTestValues = [
        self::KEY_USER => self::DEFAULT_USER,
        self::KEY_WEBSITE => self::DEFAULT_WEBSITE_URL,
        self::KEY_STATE => self::DEFAULT_STATE,
    ];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->taskFactory = new TaskFactory($container);
    }

    /**
     * @param array $testValues
     *
     * @return Test
     */
    public function create(array $testValues)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->defaultTestValues as $key => $value) {
            if (!isset($testValues[$key])) {
                $testValues[$key] = $value;
            }
        }

        $testValues[self::KEY_WEBSITE] = new NormalisedUrl($testValues[self::KEY_WEBSITE]);

        $test = new Test();

        $test->setTestId($testValues[self::KEY_TEST_ID]);
        $test->setUser($testValues[self::KEY_USER]);
        $test->setWebsite($testValues[self::KEY_WEBSITE]);
        $test->setState($testValues[self::KEY_STATE]);

        $entityManager->persist($test);
        $entityManager->flush();

        if (isset($testValues[self::KEY_TASKS])) {
            $this->taskFactory->createCollection($test, $testValues[self::KEY_TASKS]);
        }

        return $test;
    }
}
