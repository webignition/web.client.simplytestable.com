<?php

namespace Tests\WebClientBundle\Services;

use GuzzleHttp\Handler\MockHandler;
use SimplyTestable\WebClientBundle\Services\HttpClientFactory;

class TestHttpClientFactory extends HttpClientFactory
{
    /**
     * @var MockHandler
     */
    private $mockHandler;

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
}
