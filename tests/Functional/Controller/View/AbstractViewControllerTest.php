<?php

namespace App\Tests\Functional\Controller\View;

use App\Services\RemoteTestService;
use App\Services\TestRetriever;
use App\Services\TestService;
use App\Tests\Services\ObjectReflector;
use App\Controller\AbstractBaseViewController;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;
use Twig_Environment;

abstract class AbstractViewControllerTest extends AbstractControllerTest
{
    const IE6_USER_AGENT = 'Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)';

    /**
     * @var HttpHistoryContainer
     */
    protected $httpHistory;

    /**
     * @var HttpMockHandler
     */
    protected $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpHistory = self::$container->get(HttpHistoryContainer::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    protected function setTwigOnController(Twig_Environment $twig, AbstractBaseViewController $controller)
    {
        ObjectReflector::setProperty(
            $controller,
            AbstractBaseViewController::class,
            'twig',
            $twig
        );
    }

    protected function issueIERequest(string $routeName, array $routeParameters = [])
    {
        $url = $this->router->generate($routeName, $routeParameters);

        $this->client->request('GET', $url, [], [], [
            'HTTP_USER_AGENT' => self::IE6_USER_AGENT,
        ]);
    }

    protected function assertIEFilteredRedirectResponse()
    {
        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(getenv('MARKETING_SITE'), $response->getTargetUrl());
    }

    protected function setTestRetrieverOnController(
        $controller,
        TestRetriever $testRetriever
    ) {
        ObjectReflector::setProperty(
            $controller,
            get_class($controller),
            'testRetriever',
            $testRetriever
        );
    }

    protected function setTestServiceOnController(
        AbstractBaseViewController $controller,
        TestService $testService
    ) {
        ObjectReflector::setProperty(
            $controller,
            get_class($controller),
            'testService',
            $testService
        );
    }

    protected function setRemoteTestServiceOnController(
        AbstractBaseViewController $controller,
        RemoteTestService $remoteTestService,
        ?string $controllerClass = null
    ) {
        if (null === $controllerClass) {
            $controllerClass = get_class($controller);
        }

        ObjectReflector::setProperty(
            $controller,
            $controllerClass,
            'remoteTestService',
            $remoteTestService
        );
    }
}
