<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\ResultsController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\TestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ResultsControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results.html.twig';
    const ROUTE_NAME = 'view_test_results';
    const ROUTE_NAME_VERBOSE = 'view_test_results_verbose';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

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

    public function testIsIEFilteredDefaultRoute()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIsIEFilteredVerboseRoute()
    {
        $this->issueIERequest(self::ROUTE_NAME_VERBOSE, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, string $expectedRedirectUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionInvalidGetRequestDataProvider(): array
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRedirectUrl' => '/signout/',
            ],
            'invalid owner, not logged in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedRedirectUrl' => sprintf(
                    '/signin/?redirect=%s%s',
                    'eyJyb3V0ZSI6InZpZXdfdGVzdF9wcm9ncmVzcyIsInBhcmFtZXRlcnMiOnsid2Vic2l0ZSI6Imh0dHA6XC9cL2V4',
                    'YW1wbGUuY29tXC8iLCJ0ZXN0X2lkIjoiMSJ9fQ%3D%3D'
                ),
            ],
            'incomplete test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                ],
                'expectedRedirectUrl' => '/http://foo.example.com//1/',
            ],
            'failed test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                    ])),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'rejected test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_REJECTED,
                    ])),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/rejected/',
            ],
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser(new User(self::USER_EMAIL));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/unauthorised/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionPublicUserGetRequestDataProvider
     */
    public function testIndexActionPublicUserGetRequest(
        string $website,
        int $testId,
        string $filter,
        array $httpFixtures
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, [
                'website' => $website,
                'test_id' => $testId,
                'filter' => $filter,
            ])
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function indexActionPublicUserGetRequestDataProvider(): array
    {
        return [
            'default' => [
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
            ],
            'integer in website url path' => [
                'website' => 'http://example.com/articles/foo/bar/6875374/foobar/',
                'testId' => self::TEST_ID,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://example.com/articles/foo/bar/6875374/foobar/',
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                    HttpResponseFactory::createSuccessResponse(),
                ],
            ],
        ];
    }

    public function testIndexActionVerboseRoutePublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
            HttpResponseFactory::createJsonResponse($this->remoteTasksData),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(
                self::ROUTE_NAME_VERBOSE,
                array_merge($this->routeParameters, ['filter' => 'with-errors'])
            )
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        string $expectedRedirectUrl,
        array $expectedRequestUrls
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        /* @var RedirectResponse $response */
        $response = $resultsController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $this->httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrls());
        }
    }

    public function indexActionRedirectDataProvider(): array
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
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
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
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=with-errors',
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
                    'filter' => ResultsController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=without-errors',
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
                    'filter' => ResultsController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=with-warnings',
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
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        array $testValues,
        ?string $taskType,
        string $filter,
        Twig_Environment $twig
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);
        $this->setTwigOnController($twig, $resultsController);

        $response = $resultsController->indexAction(
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

    public function indexActionRenderDataProvider(): array
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
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_WITH_ERRORS,
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
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_WITH_ERRORS,
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
                'filter' => ResultsController::FILTER_ALL,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_ALL,
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
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_WITH_ERRORS,
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
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_WITH_ERRORS,
                                    'filter_label' => 'With Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
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
                'filter' => ResultsController::FILTER_WITHOUT_ERRORS,
                'twig' => MockFactory::createTwig([
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
                                    'filter' => ResultsController::FILTER_WITHOUT_ERRORS,
                                    'filter_label' => 'Without Errors',
                                    'available_task_types' => [
                                        'html-validation',
                                        'css-validation',
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
            HttpResponseFactory::createJsonResponse($this->remoteTasksData),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([
            'filter' => ResultsController::FILTER_WITH_ERRORS,
        ]);

        self::$container->get('request_stack')->push($request);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $response = $resultsController->indexAction(
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
        $newResponse = $resultsController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertParameterData(array $expectedParameterData, array $parameters)
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

    private function assertStandardViewData(string $viewName, array $parameters)
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
            'link-integrity',
        ], array_keys($parameters['task_types']));

        $this->assertInternalType('array', $parameters['test_options']);
        $this->assertInternalType('array', $parameters['css_validation_ignore_common_cdns']);
        $this->assertInternalType('array', $parameters['default_css_validation_options']);
    }

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
                'tasks',
                'filtered_task_counts',
                'domain_test_count',
                'default_css_validation_options',
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
