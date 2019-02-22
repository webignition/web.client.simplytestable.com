<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Exception\CoreApplicationRequestException;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceGetFinishedWebsitesTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedWebsitesFailureDataProvider
     */
    public function testGetFinishedWebsitesFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->getFinishedWebsites();
    }

    public function getFinishedWebsitesFailureDataProvider(): array
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

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
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
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
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    /**
     * @dataProvider getFinishedWebsitesSuccessDataProvider
     */
    public function testGetFinishedWebsitesSuccess(
        array $httpFixtures,
        array $expectedResponse,
        string $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedWebsites();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    public function getFinishedWebsitesSuccessDataProvider(): array
    {
        $websites = [
            'http://foo.example.com',
            'http://bar.example.com',
        ];

        $successfulRequestUrl = 'http://null/jobs/list/websites/?'
            . 'exclude-states%5B0%5D=cancelled&exclude-states%5B1%5D=rejected&exclude-current=1';

        return [
            'success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($websites),
                ],
                'expectedResponse' => $websites,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
        ];
    }
}
