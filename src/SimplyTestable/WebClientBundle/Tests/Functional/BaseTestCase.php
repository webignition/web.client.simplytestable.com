<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Application
     */
    private $application;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getKernel()->getContainer();
        $this->application = new Application(self::$kernel);
        $this->application->setAutoExit(false);

        $this->container->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @param array $fixtures
     */
    protected function setHttpFixtures(array $fixtures)
    {
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $client =  $httpClientService->get();

        $plugin = new MockPlugin();

        foreach ($fixtures as $fixture) {
            if ($fixture instanceof CurlException) {
                $plugin->addException($fixture);
            } else {
                $plugin->addResponse($fixture);
            }
        }

        $client->addSubscriber($plugin);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        if (!is_null($this->container)) {
            $this->container->get('doctrine')->getConnection()->close();
        }

        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }
}
