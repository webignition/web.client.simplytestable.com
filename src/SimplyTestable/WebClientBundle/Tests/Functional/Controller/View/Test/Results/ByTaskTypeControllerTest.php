<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results;

use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Controller\View\Test\Results\ByTaskTypeController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\Test\Task\ErrorTaskMapCollection;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ByTaskTypeControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Results/ByTaskType:index.html.twig';
    const ROUTE_NAME_DEFAULT = 'view_test_results_bytasktype_index_default';
    const ROUTE_NAME_FILTER = 'view_test_results_bytasktype_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var ByTaskTypeController
     */
    private $byTaskTypeController;

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

        $this->byTaskTypeController = new ByTaskTypeController();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setHttpFixtures([$httpFixtures[0]]);

        if (count($httpFixtures) > 1) {
            $this->setCoreApplicationHttpClientHttpFixtures([$httpFixtures[1]]);
        }

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME_DEFAULT, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_type' => Task::TYPE_HTML_VALIDATION,
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
                'expectedRedirectUrl' => 'http://localhost/http://foo.example.com//1/results/HTML+validation/by-error/',
            ],
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME_DEFAULT, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_type' => Task::TYPE_HTML_VALIDATION,
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
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
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
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

        $router = $this->container->get('router');
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
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $taskType
     * @param string $filter
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        $taskType,
        $filter,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $userService->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $httpHistoryPlugin = new HistoryPlugin();
        $coreApplicationHttpClient->getHttpClient()->addSubscriber($httpHistoryPlugin);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->byTaskTypeController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->byTaskTypeController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID,
            $taskType,
            $filter
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $lastRequest = $httpHistoryPlugin->getLastRequest();

        if (empty($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
        }
    }

    /**
     * @return array
     */
    public function indexActionRedirectDataProvider()
    {
        return [
            'empty task type' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'taskType' => '',
                'filter' => '',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'invalid task type' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'taskType' => 'foo',
                'filter' => '',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
                'expectedRequestUrls' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'empty filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => '',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/html+validation/by-error/',
                'expectedRequestUrls' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'invalid filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => 'foo',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/html+validation/by-error/',
                'expectedRequestUrls' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'requires preparation' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'task_count' => 1000,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/preparing/',
                'expectedRequestUrls' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
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
        $userService = $this->container->get('simplytestable.services.userservice');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userService->setUser($user);
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
                'simplytestable.services.testservice',
                'simplytestable.services.remotetestservice',
                'simplytestable.services.userservice',
                'simplytestable.services.cachevalidator',
                'simplytestable.services.urlviewvalues',
                'simplytestable.services.taskservice',
                'simplytestable.services.taskcollectionfilterservice',
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
            ]
        );

        $this->byTaskTypeController->setContainer($container);

        $response = $this->byTaskTypeController->indexAction(
            new Request(),
            self::WEBSITE,
            self::TEST_ID,
            $taskType,
            $filter
        );
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'public user, private test, no tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'user' => new User(self::USER_EMAIL),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
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
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'testValues' => [],
                'taskType' => Task::TYPE_HTML_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'testValues' => [],
                'taskType' => Task::TYPE_CSS_VALIDATION,
                'filter' => ByTaskTypeController::FILTER_BY_ERROR,
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);
        $this->byTaskTypeController->setContainer($this->container);

        $response = $this->byTaskTypeController->indexAction(
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
        $newResponse = $this->byTaskTypeController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID,
            Task::TYPE_HTML_VALIDATION,
            ByTaskTypeController::FILTER_BY_ERROR
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

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertStandardViewData($viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);
        $this->assertInternalType('array', $parameters['website']);
        $this->assertInstanceOf(ErrorTaskMapCollection::class, $parameters['error_task_maps']);

        /* @var Test $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());
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
                'public_site',
                'external_links',
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
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
