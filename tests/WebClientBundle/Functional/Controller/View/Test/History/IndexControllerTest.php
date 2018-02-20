<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\History;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\History\IndexController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends AbstractBaseTestCase
{
    const INDEX_ACTION_VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/History/Index:index.html.twig';
    const VIEW_NAME = 'view_test_history_index_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'max_results' => 90,
                'limit' => IndexController::TEST_LIST_LIMIT,
                'offset' => 90,
                'jobs' => [],
            ]),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadPageNumberDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionBadPageNumber(
        array $httpFixtures,
        Request $request,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $user = SystemUserService::getPublicUser();
        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEmpty($httpHistory->count());
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
    public function indexActionBadPageNumberDataProvider()
    {
        return [
            'no page number' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/history/',
                'expectedRequestUrls' => [],
            ],
            'zero page number' => [
                'httpFixtures' => [],
                'request' => new Request([], [], [
                    'page_number' => 0,
                ]),
                'expectedRedirectUrl' => 'http://localhost/history/',
                'expectedRequestUrls' => [],
            ],
            'negative page number' => [
                'httpFixtures' => [],
                'request' => new Request([], [], [
                    'page_number' => -1,
                ]),
                'expectedRedirectUrl' => 'http://localhost/history/',
                'expectedRequestUrls' => [],
            ],
            'greater than the number of pages: page number 10' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [
                            [
                                'id' => 1,
                                'task_types' => [],
                                'user' => 'user@example.com',
                                'state' => Test::STATE_IN_PROGRESS,
                                'type' => Test::TYPE_SINGLE_URL,
                                'task_count' => 10,
                                'website' => 'http://example.com/',
                            ],
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([1]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 1,
                            'url' => 'http://example.com',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ]),
                ],
                'request' => new Request([], [], [
                    'page_number' => 10,
                ]),
                'expectedRedirectUrl' => 'http://localhost/history/9/',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/90/?exclude-states%5B0%5D=rejected&exclude-current=1',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'greater than the number of pages: page number 10; has filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'request' => new Request([
                    'filter' => 'foo',
                ], [], [
                    'page_number' => 10,
                ]),
                'expectedRedirectUrl' => 'http://localhost/history/9/?filter=foo',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/90/?exclude-states%5B0%5D=rejected&exclude-current=1&url-filter=foo',
                ],
            ],
            'greater than the number of pages: page number 20' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 190,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 10,
                        'jobs' => [],
                    ]),
                ],
                'request' => new Request([], [], [
                    'page_number' => 20,
                ]),
                'expectedRedirectUrl' => 'http://localhost/history/19/',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/190/?exclude-states%5B0%5D=rejected&exclude-current=1',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        Request $request,
        EngineInterface $templatingEngine
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'SimplyTestable\WebClientBundle\Services\TestService',
                'SimplyTestable\WebClientBundle\Services\RemoteTestService',
                'SimplyTestable\WebClientBundle\Services\UserService',
                'SimplyTestable\WebClientBundle\Services\TaskService',
                'simplytestable.services.cachevalidator',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        return [
            'page_number: 1' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 1,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(TestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                'http://localhost/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 1; with filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([
                    'filter' => 'foo',
                ], [], [
                    'page_number' => 1,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                              $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(TestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEquals('foo', $parameters['filter']);
                            $this->assertEquals(
                                'http://localhost/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 3' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 3,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(TestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                'http://localhost/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 11' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 1000,
                        'limit' => IndexController::TEST_LIST_LIMIT,
                        'offset' => 100,
                        'jobs' => [
                            [
                                'id' => 1,
                                'task_types' => [],
                                'user' => 'user@example.com',
                                'state' => Test::STATE_COMPLETED,
                            ],
                        ],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 11,
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(TestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    11, 12, 13, 14, 15, 16, 17, 18, 19, 20
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                'http://localhost/history/websites/',
                                $parameters['websites_source']
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
            HttpResponseFactory::createJsonResponse([
                'max_results' => 0,
                'limit' => IndexController::TEST_LIST_LIMIT,
                'offset' => 0,
                'jobs' => [],
            ]),
        ]);

        $request = new Request([], [], [
            'page_number' => 1,
        ]);

        $this->container->get('request_stack')->push($request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
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
                'test_list',
                'pagination_page_numbers',
                'filter',
                'websites_source',
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