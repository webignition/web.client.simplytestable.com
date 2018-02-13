<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional;

use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractBaseTestCase extends WebTestCase
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
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->client = static::createClient();
        $this->container = $this->client->getKernel()->getContainer();

        $this->container->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @param array $httpFixtures
     */
    protected function setCoreApplicationHttpClientHttpFixtures(array $httpFixtures = [])
    {
        if (!empty($httpFixtures)) {
            $mockPlugin = new MockPlugin($httpFixtures);

            $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
            $coreApplicationHttpClient->getHttpClient()->addSubscriber($mockPlugin);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
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
