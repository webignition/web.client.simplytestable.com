<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\Results;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\Results\IndexController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService;
use SimplyTestable\WebClientBundle\Services\TaskService;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Results/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_results_index_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

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
     * @var array
     */
    private $remoteTasksData = [
        [
            'id' => 1,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_HTML_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 2,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_CSS_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 0,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 3,
            'url' => 'http://example.com/foo',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_HTML_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 4,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_CSS_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
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
    public function indexActionInvalidGetRequestDataProvider()
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'invalid owner, not logged in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedRedirectUrl' => sprintf(
                    'http://localhost/signin/?redirect=%s%s',
                    'eyJyb3V0ZSI6InZpZXdfdGVzdF9wcm9ncmVzc19pbmRleF9pbmRleCIsInBhcmFtZXRlcnMiOnsid2Vic2l0ZSI6I',
                    'mh0dHA6XC9cL2V4YW1wbGUuY29tXC8iLCJ0ZXN0X2lkIjoiMSJ9fQ%3D%3D'
                ),
            ],
            'incomplete test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
            ],
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
            ],
            'failed test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                    ])),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'rejected test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_REJECTED,
                    ])),
                ],
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/rejected/',
            ],
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userSerializerService = $this->container->get(UserSerializerService::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Not authorised', $response->getContent());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
            HttpResponseFactory::createJsonResponse($this->remoteTasksData),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'filter' => IndexController::FILTER_WITH_ERRORS,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->indexController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $requestedUrls = [];

            foreach ($httpHistory as $httpTransaction) {
                /* @var RequestInterface $guzzleRequest */
                $guzzleRequest = $httpTransaction['request'];
                $requestedUrls[] = $guzzleRequest->getUrl();
            }

            $this->assertEquals($expectedRequestUrls, $requestedUrls);
        }
    }

    /**
     * @return array
     */
    public function indexActionRedirectDataProvider()
    {
        return [
            'requires preparation' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'task_count' => 1000,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/preparing/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
            'invalid filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([
                    'filter' => 'foo',
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/?filter=with-errors',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'non-relevant filter; filter=with-errors, one task with no errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 1,
                            'url' => 'http://example.com/',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'output' => [
                                'output' => '',
                                'content-type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ],
                        ],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([
                    'filter' => IndexController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/?filter=without-errors',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'non-relevant filter; filter=with-errors, one task with no errors and with warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 1,
                            'url' => 'http://example.com/',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'output' => [
                                'output' => '',
                                'content-type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 1,
                            ],
                        ],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([
                    'filter' => IndexController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/?filter=with-warnings',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param array $testValues
     * @param string $taskType
     * @param string $filter
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        array $testValues,
        $taskType,
        $filter,
        EngineInterface $templatingEngine
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                TestService::class,
                RemoteTestService::class,
                UserService::class,
                'SimplyTestable\WebClientBundle\Services\CacheValidatorService',
                'SimplyTestable\WebClientBundle\Services\UrlViewValuesService',
                TaskService::class,
                TaskCollectionFilterService::class,
                'SimplyTestable\WebClientBundle\Services\TaskTypeService',
                'SimplyTestable\WebClientBundle\Services\TestOptions\Adapter\Factory',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'css-validation-ignore-common-cdns',
                'js-static-analysis-ignore-common-cdns',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction(
            new Request([
                'type' => $taskType,
                'filter' => $filter,
            ]),
            self::WEBSITE,
            self::TEST_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'public user, public test, html validation, with errors, null domain test count' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'is_public' => true,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => IndexController::FILTER_WITH_ERRORS,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => true,
                                    'is_public_user_test' => true,
                                    'is_owner' => true,
                                    'type' => Task::TYPE_HTML_VALIDATION,
                                    'type_label' => Task::TYPE_HTML_VALIDATION,
                                    'filter' => IndexController::FILTER_WITH_ERRORS,
                                    'filter_label' => 'With Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
                                    ],
                                    'taskIds' => [1, 3],
                                    'filtered_task_counts' => [
                                        'all' => 2,
                                        'with_errors' => 2,
                                        'with_warnings' => 0,
                                        'without_errors' => 0,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => null,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, html validation, with errors, has domain test count' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'is_public' => true,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createJsonResponse(99),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => IndexController::FILTER_WITH_ERRORS,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => true,
                                    'is_public_user_test' => true,
                                    'is_owner' => true,
                                    'type' => Task::TYPE_HTML_VALIDATION,
                                    'type_label' => Task::TYPE_HTML_VALIDATION,
                                    'filter' => IndexController::FILTER_WITH_ERRORS,
                                    'filter_label' => 'With Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
                                    ],
                                    'taskIds' => [1, 3],
                                    'filtered_task_counts' => [
                                        'all' => 2,
                                        'with_errors' => 2,
                                        'with_warnings' => 0,
                                        'without_errors' => 0,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => 99,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, no task type, all' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'is_public' => true,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createJsonResponse(99),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => null,
                'filter' => IndexController::FILTER_ALL,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => true,
                                    'is_public_user_test' => true,
                                    'is_owner' => true,
                                    'type' => '',
                                    'type_label' => 'All',
                                    'filter' => IndexController::FILTER_ALL,
                                    'filter_label' => 'All',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
                                    ],
                                    'taskIds' => [1, 2, 3, 4],
                                    'filtered_task_counts' => [
                                        'all' => 4,
                                        'with_errors' => 3,
                                        'with_warnings' => 0,
                                        'without_errors' => 1,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => 99,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, public test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'is_public' => true,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createJsonResponse(99),
                ],
                'user' => new User(self::USER_EMAIL),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => IndexController::FILTER_WITH_ERRORS,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => true,
                                    'is_public_user_test' => true,
                                    'is_owner' => false,
                                    'type' => Task::TYPE_HTML_VALIDATION,
                                    'type_label' => Task::TYPE_HTML_VALIDATION,
                                    'filter' => IndexController::FILTER_WITH_ERRORS,
                                    'filter_label' => 'With Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                    ],
                                    'taskIds' => [1, 3],
                                    'filtered_task_counts' => [
                                        'all' => 2,
                                        'with_errors' => 2,
                                        'with_warnings' => 0,
                                        'without_errors' => 0,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => 99,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, private test, html validation, with errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => self::USER_EMAIL,
                        'is_public' => false,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createJsonResponse(99),
                ],
                'user' => new User(self::USER_EMAIL),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => IndexController::FILTER_WITH_ERRORS,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => false,
                                    'is_public_user_test' => false,
                                    'is_owner' => true,
                                    'type' => Task::TYPE_HTML_VALIDATION,
                                    'type_label' => Task::TYPE_HTML_VALIDATION,
                                    'filter' => IndexController::FILTER_WITH_ERRORS,
                                    'filter_label' => 'With Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
                                        'js-static-analysis',
                                        'link-integrity',
                                    ],
                                    'taskIds' => [1, 3],
                                    'filtered_task_counts' => [
                                        'all' => 2,
                                        'with_errors' => 2,
                                        'with_warnings' => 0,
                                        'without_errors' => 0,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => 99,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, private test, css validation, without errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => self::USER_EMAIL,
                        'is_public' => false,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createJsonResponse(99),
                ],
                'user' => new User(self::USER_EMAIL),
                'testValues' => [],
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'filter' => IndexController::FILTER_WITHOUT_ERRORS,
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_public' => false,
                                    'is_public_user_test' => false,
                                    'is_owner' => true,
                                    'type' => Task::TYPE_CSS_VALIDATION,
                                    'type_label' => Task::TYPE_CSS_VALIDATION,
                                    'filter' => IndexController::FILTER_WITHOUT_ERRORS,
                                    'filter_label' => 'Without Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
                                        'js-static-analysis',
                                        'link-integrity',
                                    ],
                                    'taskIds' => [2],
                                    'filtered_task_counts' => [
                                        'all' => 2,
                                        'with_errors' => 1,
                                        'with_warnings' => 0,
                                        'without_errors' => 1,
                                        'skipped' => 0,
                                        'cancelled' => 0,
                                    ],
                                    'domain_test_count' => 99,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
            HttpResponseFactory::createJsonResponse($this->remoteTasksData),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([
            'filter' => IndexController::FILTER_WITH_ERRORS,
        ]);

        $this->container->get('request_stack')->push($request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }


    /**
     * @param array $expectedParameterData
     * @param array $parameters
     */
    private function assertParameterData($expectedParameterData, $parameters)
    {
        $this->assertEquals($expectedParameterData['is_public'], $parameters['is_public']);
        $this->assertEquals($expectedParameterData['is_public_user_test'], $parameters['is_public_user_test']);
        $this->assertEquals($expectedParameterData['is_owner'], $parameters['is_owner']);
        $this->assertEquals($expectedParameterData['type'], $parameters['type']);
        $this->assertEquals($expectedParameterData['type_label'], $parameters['type_label']);
        $this->assertEquals($expectedParameterData['filter'], $parameters['filter']);
        $this->assertEquals($expectedParameterData['filter_label'], $parameters['filter_label']);
        $this->assertEquals($expectedParameterData['filtered_task_counts'], $parameters['filtered_task_counts']);
        $this->assertEquals($expectedParameterData['domain_test_count'], $parameters['domain_test_count']);
        $this->assertEquals(
            $expectedParameterData['available_task_types'],
            array_keys($parameters['available_task_types'])
        );

        $taskIds = [];

        foreach ($parameters['tasks'] as $task) {
            /* @var Task $task */
            $taskIds[] = $task->getTaskId();
        }

        sort($taskIds);

        $this->assertEquals($expectedParameterData['taskIds'], $taskIds);
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertStandardViewData($viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);
        $this->assertInternalType('array', $parameters['website']);

        /* @var Test $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());

        /* @var RemoteTest $remoteTest */
        $remoteTest = $parameters['remote_test'];
        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
        $this->assertEquals(self::TEST_ID, $remoteTest->getId());
        $this->assertEquals(self::WEBSITE, $remoteTest->getWebsite());

        $this->assertEquals([
            'html-validation',
            'css-validation',
            'js-static-analysis',
            'link-integrity',
        ], array_keys($parameters['task_types']));

        $this->assertInternalType('array', $parameters['test_options']);
        $this->assertInternalType('array', $parameters['css_validation_ignore_common_cdns']);
        $this->assertInternalType('array', $parameters['js_static_analysis_ignore_common_cdns']);
        $this->assertInternalType('array', $parameters['default_css_validation_options']);
        $this->assertInternalType('array', $parameters['default_js_static_analysis_options']);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'website',
                'test',
                'is_public',
                'is_public_user_test',
                'remote_test',
                'is_owner',
                'type',
                'type_label',
                'filter',
                'filter_label',
                'available_task_types',
                'task_types',
                'test_options',
                'css_validation_ignore_common_cdns',
                'js_static_analysis_ignore_common_cdns',
                'tasks',
                'filtered_task_counts',
                'domain_test_count',
                'default_css_validation_options',
                'default_js_static_analysis_options',
            ],
            array_keys($parameters)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
