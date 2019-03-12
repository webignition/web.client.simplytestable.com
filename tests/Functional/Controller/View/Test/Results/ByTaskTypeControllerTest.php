<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\ByTaskTypeController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\Test\DecoratedTest;
use App\Model\Test\Task\ErrorTaskMapCollection;
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

class ByTaskTypeControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results-by-task-type.html.twig';
    const ROUTE_NAME_DEFAULT = 'view_test_results_by_task_type';
    const ROUTE_NAME_FILTER = 'view_test_results_by_task_type_filter';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    private $defaultRouteParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
        'task_type' => Task::TYPE_HTML_VALIDATION,
    ];

    private $filterRouteParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
        'task_type' => Task::TYPE_HTML_VALIDATION,
        'filter' => 'by-error',
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
        $this->issueIERequest(self::ROUTE_NAME_DEFAULT, $this->defaultRouteParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIsIEFilteredFilteredRoute()
    {
        $this->issueIERequest(self::ROUTE_NAME_FILTER, array_merge($this->defaultRouteParameters, ['filter' => 'foo']));
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
            $this->router->generate(self::ROUTE_NAME_FILTER, $this->filterRouteParameters)
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
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                ],
                'expectedRedirectUrl' => '/http://foo.example.com//1/results/HTML+validation/by-error/',
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
            $this->router->generate(self::ROUTE_NAME_DEFAULT, $this->defaultRouteParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/unauthorised/', $response->getTargetUrl());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1,]),
            HttpResponseFactory::createJsonResponse([
                [
                    'id' => 1,
                    'url' => 'http://example.com/',
                    'state' => Task::STATE_COMPLETED,
                    'worker' => '',
                    'type' => Task::TYPE_HTML_VALIDATION,
                ],
            ]),
        ]);

        $router = self::$container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME_FILTER, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_type' => Task::TYPE_HTML_VALIDATION,
            'filter' => ByTaskTypeController::FILTER_BY_ERROR,
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
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        string $taskType,
        string $filter,
        string $expectedRedirectUrl,
        string $expectedRequestUrl
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);

        /* @var RedirectResponse $response */
        $response = $byTaskTypeController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID,
            $taskType,
            $filter
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $lastRequest = $this->httpHistory->getLastRequest();

        if (empty($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUri());
        }
    }

    public function indexActionRedirectDataProvider(): array
    {
        return [
            'empty task type' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => '',
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'invalid task type' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => 'foo',
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/',
                'expectedRequestUrls' => 'http://null/job/1/',
            ],
            'empty filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/html+validation/by-error/',
                'expectedRequestUrls' => 'http://null/job/1/',
            ],
            'invalid filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => 'foo',
                'expectedRedirectUrl' => '/http://example.com//1/results/html+validation/by-error/',
                'expectedRequestUrls' => 'http://null/job/1/',
            ],
            'requires preparation' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'task_count' => 1000,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
                'expectedRequestUrls' => 'http://null/job/1/',
            ],
            'invalid remote test' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'expectedRedirectUrl' => '/',
                'expectedRequestUrls' => 'http://null/job/1/',
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
        string $taskType,
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

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);
        $this->setTwigOnController($twig, $byTaskTypeController);

        $response = $byTaskTypeController->indexAction(
            new Request(),
            self::WEBSITE,
            self::TEST_ID,
            $taskType,
            $filter
        );
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'public user, private test, no tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => self::USER_EMAIL,
                        'owners' => [
                            self::USER_EMAIL,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_HTML_VALIDATION,
                                    'is_owner' => false,
                                    'is_public_user_test' => false,
                                    'taskIds' => [],
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, no tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'owners' => [
                            SystemUserService::PUBLIC_USER_USERNAME,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_HTML_VALIDATION,
                                    'is_owner' => true,
                                    'is_public_user_test' => true,
                                    'taskIds' => [],
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, private test, no tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => self::USER_EMAIL,
                        'owners' => [
                            self::USER_EMAIL,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => new User(self::USER_EMAIL),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_HTML_VALIDATION,
                                    'is_owner' => true,
                                    'is_public_user_test' => false,
                                    'taskIds' => [],
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, has tasks, no errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'owners' => [
                            SystemUserService::PUBLIC_USER_USERNAME,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3,]),
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
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_HTML_VALIDATION,
                                    'is_owner' => true,
                                    'is_public_user_test' => true,
                                    'taskIds' => [],
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, has tasks, has errors, html validation' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'owners' => [
                            SystemUserService::PUBLIC_USER_USERNAME,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_HTML_VALIDATION,
                                    'is_owner' => true,
                                    'is_public_user_test' => true,
                                    'taskIds' => [1, 3],
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, public test, has tasks, has errors, css validation' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                        'owners' => [
                            SystemUserService::PUBLIC_USER_USERNAME,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'testValues' => [],
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'task_type' => Task::TYPE_CSS_VALIDATION,
                                    'is_owner' => true,
                                    'is_public_user_test' => true,
                                    'taskIds' => [2],
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
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);

        $response = $byTaskTypeController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID,
            Task::TYPE_HTML_VALIDATION,
            ByTaskTypeController::FILTER_BY_ERROR
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $byTaskTypeController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID,
            Task::TYPE_HTML_VALIDATION,
            ByTaskTypeController::FILTER_BY_ERROR
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertParameterData(array $expectedParameterData, array $parameters)
    {
        $this->assertEquals($expectedParameterData['task_type'], $parameters['task_type']);
        $this->assertEquals($expectedParameterData['is_owner'], $parameters['is_owner']);
        $this->assertEquals($expectedParameterData['is_public_user_test'], $parameters['is_public_user_test']);

        $expectedTaskCount = count($expectedParameterData['taskIds']);

        $this->assertCount($expectedTaskCount, $parameters['tasks']);

        $taskIds = [];

        foreach ($parameters['tasks'] as $taskIndex => $task) {
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
        $this->assertInstanceOf(ErrorTaskMapCollection::class, $parameters['error_task_maps']);

        /* @var Test $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(DecoratedTest::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, $test->getWebsite());
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'is_owner',
                'is_public_user_test',
                'website',
                'test',
                'task_type',
                'filter',
                'tasks',
                'error_task_maps',
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
