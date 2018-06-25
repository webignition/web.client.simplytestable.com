<?php

namespace Tests\WebClientBundle\Services;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use SimplyTestable\WebClientBundle\Services\HttpClientFactory;
use webignition\Guzzle\Middleware\HttpAuthentication\HttpAuthenticationMiddleware;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class TestHttpClientFactory extends HttpClientFactory
{
    const MIDDLEWARE_HISTORY_KEY = 'history';

    /**
     * @var MockHandler
     */
    private $mockHandler;

    /**
     * @var HttpHistoryContainer
     */
    private $historyContainer;

    /**
     * @param HttpHistoryContainer $httpHistoryContainer
     * @param HttpAuthenticationMiddleware $httpAuthenticationMiddleware
     */
    public function __construct(
        HttpHistoryContainer $httpHistoryContainer,
        HttpAuthenticationMiddleware $httpAuthenticationMiddleware
    ) {
        parent::__construct($httpAuthenticationMiddleware);
        $this->historyContainer = $httpHistoryContainer;
        $this->handlerStack->push(Middleware::history($this->historyContainer), self::MIDDLEWARE_HISTORY_KEY);
    }

    /**
     * @return MockHandler
     */
    protected function createInitialHandler()
    {
        parent::createInitialHandler();

        $this->mockHandler = new MockHandler();

        return $this->mockHandler;
    }

    /**
     * @param array $fixtures
     */
    public function appendFixtures(array $fixtures)
    {
        foreach ($fixtures as $fixture) {
            $this->mockHandler->append($fixture);
        }
    }

    /**
     * @return MockHandler
     */
    public function getMockHandler()
    {
        return $this->mockHandler;
    }

    /**
     * @return HttpHistoryContainer
     */
    public function getHistory()
    {
        return $this->historyContainer;
    }
}
