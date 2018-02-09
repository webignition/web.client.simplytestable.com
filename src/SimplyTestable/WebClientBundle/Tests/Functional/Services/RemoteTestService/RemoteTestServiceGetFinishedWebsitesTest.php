<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

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
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->remoteTestService->getFinishedWebsites();
    }

    /**
     * @return array
     */
    public function getFinishedWebsitesFailureDataProvider()
    {
        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
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
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedWebsites();

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
