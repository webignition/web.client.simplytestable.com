<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\Test;

use App\Controller\Action\Test\TestController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;

class TestControllerTest extends AbstractControllerTest
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TestController
     */
    private $testController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [
            [
                'name' => Task::TYPE_HTML_VALIDATION,
            ],
        ],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testController = self::$container->get(TestController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    /**
     * @dataProvider lockUnlockActionPostRequestDataProvider
     */
    public function testLockUnlockActionPostRequest(array $httpFixtures, string $routeName, array $routeParameters)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'POST',
            $this->router->generate($routeName, $routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf(Response::class, $response);
    }

    public function lockUnlockActionPostRequestDataProvider(): array
    {
        return [
            'lock action invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'routeName' => 'action_test_lock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unlock action invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'routeName' => 'action_test_unlock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'lock action valid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => 'action_test_lock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unlock action valid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => 'action_test_unlock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
        ];
    }

    /**
     * @dataProvider cancelActionGetRequestDataProvider
     */
    public function testCancelActionGetRequest(array $httpFixtures, string $expectedRedirectUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate('action_test_cancel', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function cancelActionGetRequestDataProvider(): array
    {
        $forbiddenResponse = HttpResponseFactory::createForbiddenResponse();
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'invalid owner' => [
                'httpFixtures' => [
                    $forbiddenResponse,
                    $forbiddenResponse,
                ],
                'expectedRedirectUrl' => '/',
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'CURL exception' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
        ];
    }

    /**
     * @dataProvider cancelCrawlActionGetRequestDataProvider
     */
    public function testCancelCrawlActionGetRequest(array $httpFixtures)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate('action_test_cancel_crawl', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/progress/', $response->getTargetUrl());
    }

    public function cancelCrawlActionGetRequestDataProvider(): array
    {
        $forbiddenResponse = HttpResponseFactory::createForbiddenResponse();
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'invalid owner' => [
                'httpFixtures' => [
                    $forbiddenResponse,
                    $forbiddenResponse,
                ],
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
            ],
            'CURL exception' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'crawl' => [
                            'id' => 2,
                        ]
                    ])),
                    HttpResponseFactory::createSuccessResponse(),
                ],
            ],
        ];
    }

    public function testRetestActionGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            new \GuzzleHttp\Psr7\Response(),
            HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                'id' => 2,
            ])),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate('action_test_retest', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//2/progress/', $response->getTargetUrl());
    }
}
