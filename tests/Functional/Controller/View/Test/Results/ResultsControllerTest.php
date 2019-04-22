<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\ResultsController;
use App\Entity\Task\Task;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;
use App\Model\TestInterface;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\OutputFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestModelFactory;
use App\Tests\Services\SymfonyRequestFactory;
use App\Tests\Services\ObjectReflector;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
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
        'state' => TestModel::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'type' => TestModel::TYPE_FULL_SITE,
        'taskTypes' => [
            Task::TYPE_HTML_VALIDATION,
        ],
    ];

    private $taskValuesCollection = [
        [
            TaskFactory::KEY_TASK_ID => 1,
            TaskFactory::KEY_URL => 'http://example.com/',
            TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
            TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
            TaskFactory::KEY_OUTPUT => [
                OutputFactory::KEY_ERROR_COUNT => 1,
            ],
        ],
        [
            TaskFactory::KEY_TASK_ID => 2,
            TaskFactory::KEY_URL => 'http://example.com/',
            TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
            TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
            TaskFactory::KEY_OUTPUT => [
                OutputFactory::KEY_ERROR_COUNT => 0,
            ],
        ],
        [
            TaskFactory::KEY_TASK_ID => 3,
            TaskFactory::KEY_URL => 'http://example.com/foo',
            TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
            TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
            TaskFactory::KEY_OUTPUT => [
                OutputFactory::KEY_ERROR_COUNT => 1,
            ],
        ],
        [
            TaskFactory::KEY_TASK_ID => 4,
            TaskFactory::KEY_URL => 'http://example.com/foo',
            TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
            TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
            TaskFactory::KEY_OUTPUT => [
                OutputFactory::KEY_ERROR_COUNT => 1,
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
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => TestModel::STATE_IN_PROGRESS,
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                ],
                'expectedRedirectUrl' => '/http://foo.example.com//1/results/',
            ],
            'failed test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => TestModel::STATE_FAILED_NO_SITEMAP,
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/failed/no-urls-detected/',
            ],
            'rejected test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => TestModel::STATE_REJECTED,
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
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
     * @dataProvider indexActionPublicUserGetRequestNoTasksDataProvider
     */
    public function testIndexActionPublicUserGetRequestNoTasks(
        string $website,
        int $testId,
        string $filter,
        array $httpFixtures,
        string $expectedRedirectUrl
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

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $this->assertEquals(
            [
                'http://null/user/public/authenticate/',
                'http://null/job/1/is-authorised/',
                'http://null/job/1/',
                'http://null/job/1/tasks/ids/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    public function indexActionPublicUserGetRequestNoTasksDataProvider(): array
    {
        return [
            'default' => [
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
            ],
            'integer in website url path' => [
                'website' => 'http://example.com/articles/foo/bar/6875374/foobar/',
                'testId' => self::TEST_ID,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(true),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://example.com/articles/foo/bar/6875374/foobar/',
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
                ],
                'expectedRedirectUrl' =>
                    '/http://example.com/articles/foo/bar/6875374/foobar//1/results/preparing/',
            ],
        ];
    }

    public function testIndexActionVerboseRoutePublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4, ]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(
                self::ROUTE_NAME_VERBOSE,
                array_merge($this->routeParameters, ['filter' => 'with-errors'])
            )
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/results/preparing/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionRedirect(
        array $taskValuesCollection,
        array $testModelProperties,
        Request $request,
        string $expectedRedirectUrl
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));
        $testEntity = $testModel->getEntity();

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($testEntity);
        $entityManager->flush();

        $taskFactory = new TaskFactory(self::$container);
        $taskFactory->createCollection($testEntity, $taskValuesCollection);

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser(SystemUserService::getPublicUser());

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($resultsController, $testRetriever);

        /* @var RedirectResponse $response */
        $response = $resultsController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionRedirectDataProvider(): array
    {
        return [
            'requires preparation' => [
                'taskValuesCollection' => [],
                'testModelProperties' => [
                    'remoteTaskCount' => 1000,
                ],
                'request' => new Request(),
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
            ],
            'invalid filter' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_OUTPUT => [
                            OutputFactory::KEY_ERROR_COUNT => 1,
                        ],
                    ],
                ],
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                ],
                'request' => new Request([
                    'filter' => 'foo',
                ]),
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=with-errors',
            ],
            'non-relevant filter; filter=with-errors, one task with no errors' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_OUTPUT => [
                            OutputFactory::KEY_ERROR_COUNT => 0,
                            OutputFactory::KEY_WARNING_COUNT => 0,
                        ],
                    ],
                ],
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                ],
                'request' => new Request([
                    'filter' => ResultsController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=without-errors',
            ],
            'non-relevant filter; filter=with-errors, one task with no errors and with warnings' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_OUTPUT => [
                            OutputFactory::KEY_ERROR_COUNT => 0,
                            OutputFactory::KEY_WARNING_COUNT => 1,
                        ],
                    ],
                ],
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                ],
                'request' => new Request([
                    'filter' => ResultsController::FILTER_WITH_ERRORS,
                ]),
                'expectedRedirectUrl' => '/http://example.com//1/results/?filter=with-warnings',
            ],
            'expired' => [
                'taskValuesCollection' => [],
                'testModelProperties' => [
                    'state' => TestInterface::STATE_EXPIRED,
                ],
                'request' => new Request(),
                'expectedRedirectUrl' => '/http://example.com//1/expired/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $testModelProperties,
        User $user,
        ?string $taskType,
        string $filter,
        int $domainTestCount,
        Twig_Environment $twig
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));
        $testEntity = $testModel->getEntity();
        $testEntity->setTaskIdCollection('1,2,3,4');

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($testEntity);
        $entityManager->flush();

        $taskFactory = new TaskFactory(self::$container);
        $taskFactory->createCollection($testEntity, $this->taskValuesCollection);

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($resultsController, $testRetriever);

        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('getFinishedCount')
            ->with(self::WEBSITE)
            ->andReturn($domainTestCount);

        $this->setRemoteTestServiceOnController($resultsController, $remoteTestService);
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
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User(self::USER_EMAIL);

        return [
            'public user, public test, html validation, with errors, null domain test count' => [
                'testModelProperties' => [
                    'user' => $publicUser->getUsername(),
                    'isPublic' => true,
                    'owners' => [
                        $publicUser->getUsername(),
                    ],
                ],
                'user' => $publicUser,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'domainTestCount' => 0,
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
                'testModelProperties' => [
                    'user' => $publicUser->getUsername(),
                    'isPublic' => true,
                    'owners' => [
                        $publicUser->getUsername(),
                    ],
                ],
                'user' => $publicUser,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'domainTestCount' => 99,
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
                'testModelProperties' => [
                    'user' => $publicUser->getUsername(),
                    'isPublic' => true,
                    'owners' => [
                        $publicUser->getUsername(),
                    ],
                ],
                'user' => $publicUser,
                'taskType' => null,
                'filter' => ResultsController::FILTER_ALL,
                'domainTestCount' => 99,
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
                'testModelProperties' => [
                    'user' => $publicUser->getUsername(),
                    'isPublic' => true,
                    'owners' => [
                        $publicUser->getUsername(),
                    ],
                ],
                'user' => $privateUser,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'domainTestCount' => 99,
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
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'isPublic' => false,
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'user' => $privateUser,
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ResultsController::FILTER_WITH_ERRORS,
                'domainTestCount' => 99,
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
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'isPublic' => false,
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'user' => $privateUser,
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'filter' => ResultsController::FILTER_WITHOUT_ERRORS,
                'domainTestCount' => 99,
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
        $testModel = TestModelFactory::create();
        $testEntity = $testModel->getEntity();

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($testEntity);
        $entityManager->flush();

        $taskFactory = new TaskFactory(self::$container);
        $taskFactory->createCollection($testEntity, $this->taskValuesCollection);

        $request = new Request([
            'filter' => ResultsController::FILTER_WITH_ERRORS,
        ]);

        self::$container->get('request_stack')->push($request);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('getFinishedCount')
            ->with(self::WEBSITE)
            ->andReturn(0);

        $this->setTestRetrieverOnController($resultsController, $testRetriever);
        $this->setRemoteTestServiceOnController($resultsController, $remoteTestService);

        $response = $resultsController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

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
        $this->assertIsArray($parameters['website']);

        /* @var DecoratedTest $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(DecoratedTest::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, $test->getWebsite());

        $this->assertEquals([
            'html-validation',
            'css-validation',
            'link-integrity',
        ], array_keys($parameters['task_types']));

        $this->assertIsArray($parameters['test_options']);
        $this->assertIsArray($parameters['css_validation_ignore_common_cdns']);
        $this->assertIsArray($parameters['default_css_validation_options']);
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
     * @return TestRetriever|MockInterface
     */
    private function createTestRetriever(int $testId, ?TestModel $testModel)
    {
        $testRetriever = \Mockery::mock(TestRetriever::class);
        $testRetriever
            ->shouldReceive('retrieve')
            ->with($testId)
            ->andReturn($testModel);

        return $testRetriever;
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
