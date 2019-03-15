<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\ByTaskTypeController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Model\Test\Task\ErrorTaskMapCollection;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\OutputFactory;
use App\Tests\Factory\TaskFactory;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;
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
                    HttpResponseFactory::createJsonResponse([1, 2, 3]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'website' => 'http://foo.example.com/',
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3]),
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

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/results/preparing/', $response->getTargetUrl());

        $this->assertEquals(
            [
                'http://null/user/public/authenticate/',
                'http://null/job/1/',
                'http://null/job/1/tasks/ids/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionRedirect(
        RemoteTest $remoteTest,
        User $user,
        Request $request,
        string $taskType,
        string $filter,
        string $expectedRedirectUrl
    ) {
        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);

        $testService = $this->createTestService(self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($byTaskTypeController, $testService);
        $this->setRemoteTestServiceOnController($byTaskTypeController, $remoteTestService);

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
    }

    public function indexActionRedirectDataProvider(): array
    {
        return [
            'empty task type' => [
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => '',
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
            'invalid task type' => [
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => 'foo',
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
            'empty filter' => [
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => '',
                'expectedRedirectUrl' => '/http://example.com//1/results/html+validation/by-error/',
            ],
            'invalid filter' => [
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => 'foo',
                'expectedRedirectUrl' => '/http://example.com//1/results/html+validation/by-error/',
            ],
            'requires preparation' => [
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'task_count' => 1000,
                ])),
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        callable $testCreator,
        RemoteTest $remoteTest,
        array $taskValuesCollection,
        User $user,
        string $taskType,
        string $filter,
        Twig_Environment $twig
    ) {
        $test = $testCreator();

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($test);
        $entityManager->flush();

        $taskFactory = new TaskFactory(self::$container);
        $taskFactory->createCollection($test, $taskValuesCollection);

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);

        $testService = $this->createTestService(self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($byTaskTypeController, $testService);
        $this->setRemoteTestServiceOnController($byTaskTypeController, $remoteTestService);

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
        $taskValuesCollection = [
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

        return [
            'public user, private test, no tasks' => [
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => self::USER_EMAIL,
                    'owners' => [
                        self::USER_EMAIL,
                    ],
                ])),
                'taskValuesCollection' => [],
                'user' => SystemUserService::getPublicUser(),
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));
                    $test->setUser(SystemUserService::getPublicUser()->getUsername());

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ])),
                'taskValuesCollection' => [],
                'user' => SystemUserService::getPublicUser(),
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));
                    $test->setUser(self::USER_EMAIL);

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => self::USER_EMAIL,
                    'owners' => [
                        self::USER_EMAIL,
                    ],
                ])),
                'taskValuesCollection' => [],
                'user' => new User(self::USER_EMAIL),
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));
                    $test->setUser(SystemUserService::getPublicUser()->getUsername());
                    $test->setTaskIdCollection('1,2,3');

                    $entityManager = self::$container->get(EntityManagerInterface::class);
                    $entityManager->persist($test);
                    $entityManager->flush();

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ])),
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        TaskFactory::KEY_URL => 'http://example.com/',
                        TaskFactory::KEY_STATE => Task::STATE_COMPLETED,
                        TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                        TaskFactory::KEY_OUTPUT => [
                            OutputFactory::KEY_ERROR_COUNT => 0,
                        ],
                    ],
                ],
                'user' => SystemUserService::getPublicUser(),
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));
                    $test->setUser(SystemUserService::getPublicUser()->getUsername());
                    $test->setTaskIdCollection('1,2,3,4');

                    $entityManager = self::$container->get(EntityManagerInterface::class);
                    $entityManager->persist($test);
                    $entityManager->flush();

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ])),
                'taskValuesCollection' => $taskValuesCollection,
                'user' => SystemUserService::getPublicUser(),
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);
                    $test->setWebsite(new NormalisedUrl(self::WEBSITE));
                    $test->setUser(SystemUserService::getPublicUser()->getUsername());
                    $test->setTaskIdCollection('1,2,3,4');

                    $entityManager = self::$container->get(EntityManagerInterface::class);
                    $entityManager->persist($test);
                    $entityManager->flush();

                    return $test;
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ])),
                'taskValuesCollection' => $taskValuesCollection,
                'user' => SystemUserService::getPublicUser(),
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
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));
        $remoteTest = new RemoteTest(array_merge($this->remoteTestData, [
            'task_count' => 0,
        ]));

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($test);
        $entityManager->flush();

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ByTaskTypeController $byTaskTypeController */
        $byTaskTypeController = self::$container->get(ByTaskTypeController::class);

        $testService = $this->createTestService(self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($byTaskTypeController, $testService);
        $this->setRemoteTestServiceOnController($byTaskTypeController, $remoteTestService);

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
     * @return TestService|MockInterface
     */
    private function createTestService(int $testId, Test $test)
    {
        $testService = \Mockery::mock(TestService::class);
        $testService
            ->shouldReceive('get')
            ->with($testId)
            ->andReturn($test);

        return $testService;
    }

    /**
     * @return RemoteTestService|MockInterface
     */
    private function createRemoteTestService(int $testId, RemoteTest $remoteTest)
    {
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('get')
            ->with($testId)
            ->andReturn($remoteTest);

        return $remoteTestService;
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
