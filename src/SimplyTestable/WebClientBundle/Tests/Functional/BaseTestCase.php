<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional;

use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Tester\CommandTester;

abstract class BaseTestCase extends WebTestCase
{
    const FIXTURES_DATA_RELATIVE_PATH = '/../Fixtures/Data';

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

        foreach ($this->getCommands() as $command) {
            $this->application->add($command);
        }

        $this->container->get('doctrine')->getConnection()->beginTransaction();
    }

    /**
     * @return ContainerAwareCommand[]
     */
    protected function getCommands()
    {
        return array_merge([], $this->getAdditionalCommands());
    }

    /**
     * @return ContainerAwareCommand[]
     */
    protected function getAdditionalCommands()
    {
        return [];
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return int
     */
    protected function executeCommand($name, $arguments = [])
    {
        $command = $this->application->find($name);
        $commandTester = new CommandTester($command);

        $arguments['command'] = $command->getName();

        return $commandTester->execute($arguments);
    }

    /**
     * Builds a Controller object and the request to satisfy it. Attaches the request
     * to the object and to the container.
     *
     * 'kernel_controller' events are fired.
     *
     * @param $controllerClass
     * @param string $controllerMethod Name of the method that will be called on the controller
     * @param array $parameters
     * @param array $query
     * @param array $cookies
     *
     * @return Controller The built Controller object.
     */
    protected function createController(
        $controllerClass,
        $controllerMethod,
        array $parameters = array(),
        array $query = array(),
        array $cookies = array()
    ) {
        $request = $this->createWebRequest();
        $request->attributes->set('_controller', $controllerClass.'::'.$controllerMethod);
        $request->request->add($parameters);
        $request->query->add($query);

        foreach ($cookies as $cookieName => $cookieValue) {
            $request->cookies->add(array($cookieName => $cookieValue));
        }

        $this->container->set('request', $request);

        $controllerCallable = $this->getControllerCallable($request);

        /* @var Controller $controllerClass */
        $controllerClass = $controllerCallable[0];
        $controllerClass->setContainer($this->container);

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('kernel.controller', new FilterControllerEvent(
            self::$kernel,
            $controllerCallable,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        ));

        return $controllerCallable[0];
    }

    /**
     * @param string $controllerClass
     * @param string $controllerMethod
     * @param string $routeName
     * @param array $parameters
     * @param array $query
     * @param array $cookies
     *
     * @return Controller
     */
    protected function createRoutedController(
        $controllerClass,
        $controllerMethod,
        $routeName,
        array $parameters = array(),
        array $query = array(),
        array $cookies = array()
    ) {
        $request = $this->createWebRequest();
        $request->attributes->set('_controller', $controllerClass.'::'.$controllerMethod);
        $request->attributes->set('_route', $routeName);
        $request->headers->add($this->getRequestHeaders());

        foreach ($this->getRequestAttributes() as $key => $value) {
            $request->attributes->set($key, $value);
        }

        $request->request->add($parameters);
        $request->query->add($query);

        foreach ($cookies as $cookieName => $cookieValue) {
            $request->cookies->add(array($cookieName => $cookieValue));
        }

        $this->container->set('request', $request);

        $controllerCallable = $this->getControllerCallable($request);

        /* @var Controller $controllerClass */
        $controllerClass = $controllerCallable[0];
        $controllerClass->setContainer($this->container);

        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('kernel.controller', new FilterControllerEvent(
            self::$kernel,
            $controllerCallable,
            $request,
            HttpKernelInterface::MASTER_REQUEST
        ));

        return $controllerCallable[0];
    }

    /**
     * @param Request $request
     *
     * @return bool|mixed
     */
    private function getControllerCallable(Request $request)
    {
        $controllerResolver = new ControllerResolver();

        return $controllerResolver->getController($request);
    }

    /**
     * Creates a new Request object and hydrates it with the proper values to make
     * a valid web request.
     *
     * @return Request The hydrated Request object.
     */
    protected function createWebRequest()
    {
        $request = Request::createFromGlobals();
        $request->server->set('REMOTE_ADDR', '127.0.0.1');

        return $request;
    }

    /**
     * @param string $testName
     *
     * @return string
     */
    protected function getFixturesDataPath($testName = null)
    {
        $fixturesPath = $this->getCustomFixturesDataPath($testName);

        while (!$this->directoryContainsFiles($fixturesPath) && $fixturesPath != __DIR__ . '/Fixtures') {
            $pathParts = explode(DIRECTORY_SEPARATOR, $fixturesPath);
            array_pop($pathParts);
            $fixturesPath = implode(DIRECTORY_SEPARATOR, $pathParts);
        }

        return $fixturesPath;
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function directoryContainsFiles($path)
    {
        if (realpath($path) === false) {
            return false;
        }

        $directoryIterator = new \DirectoryIterator($path);
        $containsFiles = false;

        foreach ($directoryIterator as $item) {
            if ($containsFiles === true) {
                continue;
            }

            /* @var $item \DirectoryIterator */
            if ($item->isFile() && !$item->isDot() && $item->isReadable()) {
                $containsFiles = true;
            }
        }

        return $containsFiles;
    }

    /**
     * @param string $testName
     *
     * @return string
     */
    protected function getCustomFixturesDataPath($testName)
    {
        return __DIR__
            . self::FIXTURES_DATA_RELATIVE_PATH
            . '/'
            . str_replace('\\', DIRECTORY_SEPARATOR, get_class($this))
            . '/' . $testName;
    }

    /**
     * @param string $testName
     *
     * @return bool
     */
    protected function hasCustomFixturesDataPath($testName)
    {
        return realpath($this->getCustomFixturesDataPath($testName)) !== false;
    }

    /**
     * @param $fixtures
     *
     * @param HttpClient $client
     */
    protected function setHttpFixtures($fixtures, $client = null)
    {
        if (count($fixtures) === 0) {
            $this->fail('HTTP fixtures path empty or incorrect ('.$this->getFixturesDataPath($this->getName()).')');
        }

        $plugin = new MockPlugin();

        foreach ($fixtures as $fixture) {
            if ($fixture instanceof \Exception) {
                $plugin->addException($fixture);
            } else {
                $plugin->addResponse($fixture);
            }
        }

        if (is_null($client)) {
            $client =  $this->getHttpClientService()->get();
        }

        $client->addSubscriber($plugin);
    }

    /**
     * @param array $items Collection of http messages and/or curl exceptions
     *
     * @return array
     */
    protected function buildHttpFixtureSet($items)
    {
        $fixtures = array();

        foreach ($items as $item) {
            switch ($this->getHttpFixtureItemType($item)) {
                case 'httpMessage':
                    $fixtures[] = Response::fromMessage($item);
                    break;

                case 'curlException':
                    $fixtures[] = $this->getCurlExceptionFromCurlMessage($item);
                    break;

                default:
                    throw new \LogicException();
            }
        }

        return $fixtures;
    }

    /**
     * @param string $item
     * @return string
     */
    private function getHttpFixtureItemType($item)
    {
        if (substr($item, 0, strlen('HTTP')) == 'HTTP') {
            return 'httpMessage';
        }

        return 'curlException';
    }

    /**
     * @param string $curlMessage
     * @return CurlException
     */
    private function getCurlExceptionFromCurlMessage($curlMessage)
    {
        $curlMessageParts = explode(' ', $curlMessage, 2);

        $curlException = new CurlException();
        if (isset($curlMessageParts[1])) {
            $curlException->setError($curlMessageParts[1], (int)str_replace('CURL/', '', $curlMessageParts[0]));
        } else {
            $curlException->setError('Default Curl Message', (int)str_replace('CURL/', '', $curlMessageParts[0]));
        }

        return $curlException;
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getHttpFixtures($path)
    {
        return $this->buildHttpFixtureSet($this->getHttpFixtureContents($path));
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function getHttpFixtureContents($path)
    {
        $fixtureContents = array();
        $fixturesDirectory = new \DirectoryIterator($path);

        $fixturePathnames = array();

        foreach ($fixturesDirectory as $directoryItem) {
            if ($directoryItem->isFile()) {
                $fixturePathnames[] = $directoryItem->getPathname();
            }
        }

        sort($fixturePathnames);

        foreach ($fixturePathnames as $fixturePathname) {
            $fixtureContents[] = trim(file_get_contents($fixturePathname));
        }

        return $fixtureContents;
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

    /**
     * @return array
     */
    protected function getRequestAttributes()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getRequestHeaders()
    {
        return [];
    }
}
