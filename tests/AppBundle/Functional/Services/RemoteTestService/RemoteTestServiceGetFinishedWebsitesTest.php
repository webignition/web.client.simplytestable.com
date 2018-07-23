<?php

namespace Tests\AppBundle\Functional\Services\RemoteTestService;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;

class RemoteTestServiceGetFinishedWebsitesTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedWebsitesFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetFinishedWebsitesFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->getFinishedWebsites();
    }

    /**
     * @return array
     */
    public function getFinishedWebsitesFailureDataProvider()
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
     *
     * @param array $httpFixtures
     * @param array $expectedResponse
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetFinishedWebsitesSuccess(array $httpFixtures, $expectedResponse, $expectedRequestUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedWebsites();

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function getFinishedWebsitesSuccessDataProvider()
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
