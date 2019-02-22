<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceGetFinishedCountTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedCountDataProvider
     */
    public function testGetFinishedCount(
        array $httpFixtures,
        ?string $urlFilter,
        ?int $expectedResponse,
        string $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedCount($urlFilter);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    public function getFinishedCountDataProvider(): array
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
