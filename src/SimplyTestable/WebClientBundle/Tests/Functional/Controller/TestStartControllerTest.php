<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\TaskController;
use SimplyTestable\WebClientBundle\Controller\TestController;
use SimplyTestable\WebClientBundle\Controller\TestStartController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Repository\TestRepository;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestStartControllerTest extends BaseSimplyTestableTestCase
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TestStartController
     */
    private $testStartController;

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

        $this->testStartController = new TestStartController();
        $this->testStartController->setContainer($this->container);
    }

//    public function testRetestActionGetRequest()
//    {
//        $this->setHttpFixtures([
//            HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
//                'id' => 2,
//            ])),
//        ]);
//
//        $router = $this->container->get('router');
//        $requestUrl = $router->generate('app_test_retest', [
//            'website' => self::WEBSITE,
//            'test_id' => self::TEST_ID,
//        ]);
//
//        $this->client->request(
//            'GET',
//            $requestUrl
//        );
//
//        /* @var RedirectResponse $response */
//        $response = $this->client->getResponse();
//
//        $this->assertInstanceOf(RedirectResponse::class, $response);
//        $this->assertEquals('http://localhost/http://example.com//2/progress/', $response->getTargetUrl());
//    }

    /**
     * @dataProvider startNewActionDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param array $expectedFlashBagValues
     * @param array $expectedRequestUrls
     */
    public function testStartNewAction(
        array $httpFixtures,
        User $user,
        Request $request,
        $expectedRedirectUrl,
        array $expectedFlashBagValues,
        array $expectedRequestUrls
    ) {
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $httpHistory = new HttpHistory($httpClientService);

        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $session = $this->container->get('session');
        $flashBag = $session->getFlashBag();

        /* @var RedirectResponse $response */
        $response = $this->testStartController->startNewAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        foreach ($expectedFlashBagValues as $key => $value) {
            $this->assertEquals($value, $flashBag->get($key));
        }

        $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());

//        echo $response;
    }

    /**
     * @return array
     */
    public function startNewActionDataProvider()
    {
        $publicUser = new User(UserService::PUBLIC_USER_USERNAME);
        $privateUser = new User(self::USER_EMAIL);

        return [
            'website missing' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrls' => [],
            ],
            'website empty; empty string' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => '',
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrls' => [],
            ],
            'website empty; whitespace-only string' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => '   ',
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrls' => [],
            ],
            'no task types selected' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'website' => self::WEBSITE,
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'no-test-types-selected',
                    ],
                ],
                'expectedRequestUrls' => [],
            ],
            'curl exception' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'curl-error',
                    ],
                    'curl_error_code' => [
                        28,
                    ],
                ],
                'expectedRequestUrls' => [],
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                        'website' => self::WEBSITE,
                        'html-validation' => 1,
                        'css-validation' => 0,
                    ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'web_resource_exception',
                    ],
                ],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ]
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'HTTP 500; with http auth' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => 'user',
                    'http-auth-password' => 'pass',
                ]),
                'expectedRedirectUrl' => 'http://localhost/?' . http_build_query([
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'http-auth-username' => 'user',
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'web_resource_exception',
                    ],
                ],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                        'parameters' => [
                            'http-auth-username' => 'user',
                            'http-auth-password' => 'pass',
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; type=full site' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ]
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; type=single' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'full-single' => 'single',
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'single url',
                        'test-types' => [
                            'HTML validation',
                        ]
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $privateUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                        'test-type-options' => [
                            'JS+static+analysis' => [
                                'jslint-option-bitwise' => 1,
                                'jslint-option-continue' => 1,
                                'jslint-option-debug' => 1,
                                'jslint-option-evil' => 1,
                                'jslint-option-eqeq' => 1,
                                'jslint-option-forin' => 1,
                                'jslint-option-newcap' => 1,
                                'jslint-option-nomen' => 1,
                                'jslint-option-plusplus' => 1,
                                'jslint-option-regexp' => 1,
                                'jslint-option-unparam' => 1,
                                'jslint-option-sloppy' => 1,
                                'jslint-option-stupid' => 1,
                                'jslint-option-sub' => 1,
                                'jslint-option-vars' => 1,
                                'jslint-option-white' => 1,
                                'jslint-option-anon' => 1,
                            ],
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; private user; link integrity' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $privateUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'link-integrity' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'Link integrity',
                        ],
                        'test-type-options' => [
                            'JS+static+analysis' => [
                                'jslint-option-bitwise' => 1,
                                'jslint-option-continue' => 1,
                                'jslint-option-debug' => 1,
                                'jslint-option-evil' => 1,
                                'jslint-option-eqeq' => 1,
                                'jslint-option-forin' => 1,
                                'jslint-option-newcap' => 1,
                                'jslint-option-nomen' => 1,
                                'jslint-option-plusplus' => 1,
                                'jslint-option-regexp' => 1,
                                'jslint-option-unparam' => 1,
                                'jslint-option-sloppy' => 1,
                                'jslint-option-stupid' => 1,
                                'jslint-option-sub' => 1,
                                'jslint-option-vars' => 1,
                                'jslint-option-white' => 1,
                                'jslint-option-anon' => 1,
                            ],
                            'Link+integrity' => [
                                'excluded-domains' => [
                                    'instagram.com',
                                ],
                            ],
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; public user; schemeless website' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => 'example.com/',
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; public user; http auth' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => 'user',
                    'http-auth-password' => 'pass',
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                        'parameters' => [
                            'http-auth-username' => 'user',
                            'http-auth-password' => 'pass',
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; public user; empty http auth' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => '',
                    'http-auth-password' => '',
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
            'success; public user; cookies' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'cookies' => [
                        [
                            'name' => 'cookie-name-1',
                            'value' => 'cookie-value-1',
                        ],
                    ],
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedFlashBagValues' => [],
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/start/?' . http_build_query([
                        'type' => 'full site',
                        'test-types' => [
                            'HTML validation',
                        ],
                        'parameters' => [
                            'cookies' => [
                                [
                                    'name' => 'cookie-name-1',
                                    'value' => 'cookie-value-1',
                                    'path' => '/',
                                    'domain' => '.example.com',
                                ],
                            ],
                        ],
                    ], null, '&', PHP_QUERY_RFC3986),
                ],
            ],
        ];
    }
}
