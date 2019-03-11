<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\TestOptions;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\ModelFactory;

class RemoteTestServiceStartTest extends AbstractRemoteTestServiceTest
{
    const CANONICAL_URL = 'http://example.com';

    /**
     * @dataProvider startFailureDataProvider
     */
    public function testStartFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->start(
            self::CANONICAL_URL,
            ModelFactory::createTestOptions()
        );
    }

    public function startFailureDataProvider(): array
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
     */
    public function testStartSuccess(
        string $url,
        TestOptions $testOptions,
        string $expectedRequestUrl,
        array $expectedPostData
    ) {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $remoteTest = $this->remoteTestService->start(
            $url,
            $testOptions
        );

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($expectedRequestUrl, $lastRequest->getUri());
        $this->assertEquals($expectedPostData, $postedData);
    }

    public function startSuccessDataProvider(): array
    {
        return [
            'default' => [
                'url' => self::CANONICAL_URL,
                'testOptions' => ModelFactory::createTestOptions(),
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/',
                'expectedPostData' => [
                    'type' => Test::TYPE_FULL_SITE,
                    'url' => self::CANONICAL_URL,
                ],
            ],
            'custom cookies' => [
                'url' => self::CANONICAL_URL,
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
                    'type' => Test::TYPE_FULL_SITE,
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
                    'url' => self::CANONICAL_URL,
                ],
            ],
            'custom cookies; invalid url' => [
                'url' => 'foo',
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
                'expectedRequestUrl' => 'http://null/job/foo/start/',
                'expectedPostData' => [
                    'type' => Test::TYPE_FULL_SITE,
                    'url' => 'foo',
                ],
            ],
        ];
    }
}
