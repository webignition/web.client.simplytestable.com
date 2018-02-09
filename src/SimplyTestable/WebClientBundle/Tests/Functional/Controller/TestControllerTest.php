<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\TestController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
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

        $this->testController = new TestController();
        $this->testController->setContainer($this->container);
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

        $router = $this->container->get('router');
        $requestUrl = $router->generate($routeName, $routeParameters);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/http://example.com//1/results/', $response->getTargetUrl());
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

        $router = $this->container->get('router');
        $requestUrl = $router->generate('test_cancel', [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
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
        return [
            'invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
            ],
            'CURL exception' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
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

        $router = $this->container->get('router');
        $requestUrl = $router->generate('test_cancel_crawl', [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/http://example.com//1/progress/', $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function cancelCrawlActionGetRequestDataProvider()
    {
        return [
            'invalid owner' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
            ],
            'CURL exception' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
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

        $router = $this->container->get('router');
        $requestUrl = $router->generate('app_test_retest', [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/http://example.com//2/progress/', $response->getTargetUrl());
    }
}
