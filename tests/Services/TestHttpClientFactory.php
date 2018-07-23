<?php

namespace App\Tests\Services;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use App\Services\HttpClientFactory;
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
     * @param HttpMockHandler $mockHandler
     */
    public function __construct(
        HttpHistoryContainer $httpHistoryContainer,
        HttpAuthenticationMiddleware $httpAuthenticationMiddleware,
        HttpMockHandler $mockHandler
    ) {
        $this->mockHandler = $mockHandler;

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

        return $this->mockHandler;
    }
}
