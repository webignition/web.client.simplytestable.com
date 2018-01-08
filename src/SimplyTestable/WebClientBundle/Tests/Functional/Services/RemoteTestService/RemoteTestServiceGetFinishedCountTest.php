<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;

class RemoteTestServiceGetFinishedCountTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedCountDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedResponse
     * @param string $expectedRequestUrl
     */
    public function testGetFinishedCount(array $httpFixtures, $urlFilter, $expectedResponse, $expectedRequestUrl)
    {
        $this->remoteTestService->setUser($this->user);

        $this->setHttpFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedCount($urlFilter);

        $this->assertEquals($expectedResponse, $response);

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        if (is_null($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
        }
    }

    /**
     * @return array
     */
    public function getFinishedCountDataProvider()
    {
        $successfulRequestUrl = 'http://null/jobs/list/count/?exclude-states%5B0%5D=rejected&exclude-current=1';

        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => null,
            ],
            'success' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n123"),
                ],
                'urlFilter' => null,
                'expectedResponse' => 123,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'success with url filter' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n123"),
                ],
                'urlFilter' => 'foo',
                'expectedResponse' => 123,
                'expectedRequestUrl' => $successfulRequestUrl . '&url-filter=foo',
            ],
        ];
    }
}
