<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceGetFinishedCountTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedCountDataProvider
     *
     * @param array $httpFixtures
     * @param string $urlFilter
     * @param array $expectedResponse
     * @param string $expectedRequestUrl
     */
    public function testGetFinishedCount(array $httpFixtures, $urlFilter, $expectedResponse, $expectedRequestUrl)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedCount($urlFilter);

        $this->assertEquals($expectedResponse, $response);

        $lastRequest = $this->getLastRequest();

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
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => null,
            ],
            'success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(123),
                ],
                'urlFilter' => null,
                'expectedResponse' => 123,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'success with url filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(123),
                ],
                'urlFilter' => 'foo',
                'expectedResponse' => 123,
                'expectedRequestUrl' => $successfulRequestUrl . '&url-filter=foo',
            ],
        ];
    }
}
