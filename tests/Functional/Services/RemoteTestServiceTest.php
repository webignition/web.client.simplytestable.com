<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\RemoteTestService;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\Test as TestModel;
use App\Model\TestIdentifier;
use App\Model\TestOptions;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\ModelFactory;
use Psr\Http\Message\ResponseInterface;

class RemoteTestServiceTest extends AbstractCoreApplicationServiceTest
{
    const TEST_ID = 1;
    const WEBSITE = 'http://example.com/';

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->remoteTestService = self::$container->get(RemoteTestService::class);
    }

    public function testCancel()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->remoteTestService->cancel(self::TEST_ID);

        $this->assertEquals(
            'http://null/job/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testCancelByTestProperties()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->remoteTestService->cancelByTestProperties(self::TEST_ID);

        $this->assertEquals(
            'http://null/job/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

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
        $successfulRequestUrl = 'http://null/jobs/list/count/?exclude-states%5B0%5D=rejected&exclude-current=1';

        return [
            'HTTP 500' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createInternalServerErrorResponse()),
                'urlFilter' => null,
                'expectedResponse' => null,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'CURL 28' => [
                'httpFixtures' => array_fill(0, 6, ConnectExceptionFactory::create(28, 'Operation timed out')),
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
        return [
            'HTTP 500' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createInternalServerErrorResponse()),
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => array_fill(0, 6, ConnectExceptionFactory::create(28, 'Operation timed out')),
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

    /**
     * @dataProvider getSummaryDataRemoteFailureDataProvider
     */
    public function testGetSummaryDataRemoteFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->getSummaryData(1);
    }

    public function getSummaryDataRemoteFailureDataProvider(): array
    {
        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'HTTP 500' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createInternalServerErrorResponse()),
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => array_fill(0, 6, ConnectExceptionFactory::create(28, 'Operation timed out')),
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testGetSummaryDataRemoteTestNotJsonDocument()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse([
                'content-type' => 'text/plain',
            ]),
        ]);

        $remoteTest = $this->remoteTestService->getSummaryData(self::TEST_ID);

        $this->assertNull($remoteTest);
    }

    public function testGetSummaryDataSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $remoteTestSummaryData = $this->remoteTestService->getSummaryData(self::TEST_ID);

        $this->assertIsArray($remoteTestSummaryData);
        $this->assertEquals('http://null/job/1/', $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @dataProvider isAuthorisedExceptionCasesDataProvider
     */
    public function testIsAuthorisedExceptionCases(ResponseInterface $httpResponse)
    {
        $this->httpMockHandler->appendFixtures([$httpResponse]);

        $this->assertFalse($this->remoteTestService->isAuthorised(self::TEST_ID));
    }

    public function isAuthorisedExceptionCasesDataProvider(): array
    {
        return [
            'http 404' => [
                'httpResponse' => HttpResponseFactory::createNotFoundResponse(),
            ],
            'http 401' => [
                'httpResponse' => HttpResponseFactory::createUnauthorisedResponse(),
            ],
            'invalid response content type' => [
                'httpResponse' => HttpResponseFactory::createSuccessResponse(['content-type' => 'text/plain']),
            ],
        ];
    }

    /**
     * @dataProvider isAuthorisedSuccessDataProvider
     */
    public function testIsAuthorisedSuccess(bool $isAuthorised)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($isAuthorised),
        ]);

        $this->assertEquals($isAuthorised, $this->remoteTestService->isAuthorised(self::TEST_ID));
    }

    public function isAuthorisedSuccessDataProvider(): array
    {
        return [
            'not authorised' => [
                'isAuthorised' => false,
            ],
            'is authorised' => [
                'isAuthorised' => true,
            ],
        ];
    }

    public function testLock()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->remoteTestService->lock(self::TEST_ID);
        $this->assertEquals(
            'http://null/job/1/set-private/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testUnlock()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->remoteTestService->unlock(self::TEST_ID);
        $this->assertEquals(
            'http://null/job/1/set-public/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testRetest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::TEST_ID + 1,
                'website' => self::WEBSITE,
            ]),
        ]);

        $testIdentifier = $this->remoteTestService->retest(self::TEST_ID);

        $this->assertInstanceOf(TestIdentifier::class, $testIdentifier);

        $this->assertEquals(
            [
                'test_id' => self::TEST_ID + 1,
                'website' => self::WEBSITE,
            ],
            $testIdentifier->toArray()
        );

        $this->assertEquals(
            'http://null/job/1/re-test/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    /**
     * @dataProvider retrieveLatestDataProvider
     */
    public function testRetrieveLatest(
        array $httpFixtures,
        ?string $expectedRequestUrl,
        ?TestIdentifier $expectedTestIdentifier
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testIdentifier = $this->remoteTestService->retrieveLatest(self::WEBSITE);

        $this->assertEquals($expectedTestIdentifier, $testIdentifier);

        if (!is_null($expectedRequestUrl)) {
            $this->assertEquals(
                $expectedRequestUrl,
                $this->httpHistory->getLastRequestUrl()
            );
        }
    }

    public function retrieveLatestDataProvider(): array
    {
        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => null,
            ],

            'CURL 28' => [
                'httpFixtures' => array_fill(0, 6, ConnectExceptionFactory::create(28, 'Operation timed out')),
                'expectedRequestUrl' => null,
                'expectedTestIdentifier' => null,
            ],
            'Invalid response content' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => null,
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => new TestIdentifier(1, 'http://example.com/'),
            ],
        ];
    }

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

        $this->remoteTestService->start(self::WEBSITE, ModelFactory::createTestOptions());
    }

    public function startFailureDataProvider(): array
    {
        return [
            'read only' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createServiceUnavailableResponse()),
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'http 500' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createInternalServerErrorResponse()),
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
                'website' => 'http://example.com/',
            ]),
        ]);

        $testIdentifier = $this->remoteTestService->start(
            $url,
            $testOptions
        );

        $this->assertInstanceOf(TestIdentifier::class, $testIdentifier);

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($expectedRequestUrl, (string) $lastRequest->getUri());
        $this->assertEquals($expectedPostData, $postedData);
    }

    public function startSuccessDataProvider(): array
    {
        return [
            'default' => [
                'url' => self::WEBSITE,
                'testOptions' => ModelFactory::createTestOptions(),
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'url' => self::WEBSITE,
                ],
            ],
            'custom cookies' => [
                'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
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
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'url' => 'foo',
                ],
            ],
        ];
    }
}
