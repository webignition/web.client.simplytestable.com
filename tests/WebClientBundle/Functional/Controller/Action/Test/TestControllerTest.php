<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\Test;

use SimplyTestable\WebClientBundle\Controller\Action\Test\TestController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TestControllerTest extends AbstractBaseTestCase
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TestController
     */
    private $testController;

    /**
     * @var array
     */
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

        $this->testController = $this->container->get(TestController::class);
    }

    /**
     * @dataProvider lockUnlockActionGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $routeName
     * @param array $routeParameters
     */
    public function testLockUnlockActionGetRequest(array $httpFixtures, $routeName, array $routeParameters)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl($routeName, $routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/results/', $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function lockUnlockActionGetRequestDataProvider()
    {
        return [
            'lock action invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'routeName' => 'app_test_lock',
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
                'routeName' => 'app_test_unlock',
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
                'routeName' => 'app_test_lock',
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
                'routeName' => 'app_test_unlock',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
        ];
    }

    /**
     * @dataProvider cancelActionGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testCancelActionGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl('test_cancel', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function cancelActionGetRequestDataProvider()
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
     *
     * @param array $httpFixtures
     */
    public function testCancelCrawlActionGetRequest(array $httpFixtures)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl('test_cancel_crawl', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/progress/', $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function cancelCrawlActionGetRequestDataProvider()
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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                'id' => 2,
            ])),
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl('app_test_retest', [
                'website' => self::WEBSITE,
                'test_id' => self::TEST_ID,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//2/progress/', $response->getTargetUrl());
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return string
     */
    private function createRequestUrl($routeName, array $routeParameters = [])
    {
        $router = $this->container->get('router');

        return $router->generate($routeName, $routeParameters);
    }
}
