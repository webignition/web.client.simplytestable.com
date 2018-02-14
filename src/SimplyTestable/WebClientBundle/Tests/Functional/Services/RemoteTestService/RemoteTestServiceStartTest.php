<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use GuzzleHttp\Post\PostBody;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;

class RemoteTestServiceStartTest extends AbstractRemoteTestServiceTest
{
    const CANONICAL_URL = 'http://example.com';

    /**
     * @dataProvider startFailureDataProvider
     *
     * @param array $httpFixtures
     * @param $expectedException
     * @param $expectedExceptionMessage
     * @param $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testStartFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);
        $this->remoteTestService->start(
            self::CANONICAL_URL,
            ModelFactory::createTestOptions()
        );
    }

    /**
     * @return array
     */
    public function startFailureDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();

        return [
            'read only' => [
                'httpFixtures' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'http 500' => [
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
            'invalid response content' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ]),
                ],
                'expectedException' => InvalidContentTypeException::class,
                'expectedExceptionMessage' => 'Invalid content type: "text/plain"',
                'expectedExceptionCode' => 0,
            ],
            'invalid credentials' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    /**
     * @dataProvider startSuccessDataProvider
     *
     * @param TestOptions $testOptions
     * @param string $expectedRequestUrl
     * @param array $expectedPostData
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testStartSuccess(TestOptions $testOptions, $expectedRequestUrl, array $expectedPostData)
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $remoteTest = $this->remoteTestService->start(
            self::CANONICAL_URL,
            $testOptions
        );

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);

        $lastRequest = $this->getLastRequest();

        /* @var PostBody $requestBody */
        $requestBody = $lastRequest->getBody();

        $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
        $this->assertEquals($expectedPostData, $requestBody->getFields());
    }

    /**
     * @return array
     */
    public function startSuccessDataProvider()
    {
        return [
            'default' => [
                'testOptions' => ModelFactory::createTestOptions(),
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                ],
            ],
            'custom cookies' => [
                'testOptions' => ModelFactory::createTestOptions([
                    ModelFactory::TEST_OPTIONS_FEATURE_OPTIONS => [
                        'cookies' => [
                            'cookies' => [
                                [
                                    'name' => 'cookie-name',
                                    'value' => 'cookie-value',
                                ],
                            ],
                        ],
                    ],
                ]),
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'parameters' => [
                        'cookies' => [
                            [
                                'name' => 'cookie-name',
                                'value' => 'cookie-value',
                                'path' => '/',
                                'domain' => '.example.com',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
