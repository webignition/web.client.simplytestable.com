<?php

namespace Tests\WebClientBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractBaseTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        self::$container->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        self::$container->get('doctrine')->getConnection()->close();

        parent::tearDown();

        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }
}
