<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;

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
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedCount($urlFilter);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function getFinishedCountDataProvider()
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        $successfulRequestUrl = 'http://null/jobs/list/count/?exclude-states%5B0%5D=rejected&exclude-current=1';

        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => $successfulRequestUrl,
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
