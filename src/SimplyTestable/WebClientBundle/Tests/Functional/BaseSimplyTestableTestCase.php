<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional;

use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TestHttpClientService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseSimplyTestableTestCase extends BaseTestCase
{
    const TEST_START_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestStartController';
    const TASK_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TaskController';
    const TEST_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestController';
    const USER_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\UserController';
    const USER_ACCOUNT_PLAN_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\UserAccountPlanController';

    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    protected function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        return $this->user;
    }

    /**
     * @return bool
     */
    protected function hasUser()
    {
        return !is_null($this->user);
    }

    /**
     * @param string $methodName
     * @param array $postData
     *
     * @return UserController
     */
    protected function getUserController($methodName, $postData = [])
    {
        return $this->getController(self::USER_CONTROLLER_NAME, $methodName, $postData);
    }

    /**
     * @param string $controllerName
     * @param string $methodName
     * @param array $postData
     * @param array $queryData
     *
     * @return Controller
     */
    protected function getController($controllerName, $methodName, array $postData = [], array $queryData = [])
    {
        $cookieData = [];
        if ($this->hasUser()) {
            $cookieData['simplytestable-user'] = $this->getUserSerializerService()->serializeToString($this->user);
        }

        return $this->createController($controllerName, $methodName, $postData, $queryData, $cookieData);
    }

    /**
     * @param string $controllerName
     * @param string $methodName
     * @param string $routeName
     * @param array $postData
     * @param array $queryData
     *
     * @return Controller
     */
    protected function getRoutedController(
        $controllerName,
        $methodName,
        $routeName,
        array $postData = [],
        array $queryData = []
    ) {
        $cookieData = [];
        if ($this->hasUser()) {
            $cookieData['simplytestable-user'] = $this->getUserSerializerService()->serializeToString($this->user);
        }

        return $this->createRoutedController(
            $controllerName,
            $methodName,
            $routeName,
            $postData,
            $queryData,
            $cookieData
        );
    }

    /**
     * @param string $url
     * @param string $method
     *
     * @return Crawler
     */
    protected function getCrawler($url, $method = 'GET')
    {
        if ($this->hasUser()) {
            $cookie = new Cookie(
                'simplytestable-user',
                $this->getUserSerializerService()->serializeToString($this->user)
            );

            $this->client->getCookieJar()->set($cookie);
        }

        $crawler = $this->client->request($method, $url);
        return $crawler;
    }

    /**
     * @return TestHttpClientService
     */
    protected function getHttpClientService()
    {
        return $this->container->get('simplytestable.services.httpclientservice');
    }

    /**
     * @return TestService
     */
    protected function getTestService()
    {
        return $this->container->get('simplytestable.services.testservice');
    }

    /**
     * @return UserSerializerService
     */
    protected function getUserSerializerService()
    {
        return $this->container->get('simplytestable.services.userserializerservice');
    }

    /**
     * @return User
     */
    protected function makeUser()
    {
        $user = new User();
        $user->setUsername('user@example.com');
        $user->setPassword('password');
        return $user;
    }
}

