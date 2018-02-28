<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\Results\Preparing;

use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\Results\Preparing\IndexController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Factory\TaskFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class IndexControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Results/Preparing/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_results_preparing_index_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var array
     */
    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

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

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

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

        $this->assertTrue($response->isClientError());
        $this->assertEmpty($response->getContent());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3,]),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    public function testIndexActionNoRemoteTasks()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                'task_count' => 0,
            ])),
        ]);

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param $website
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionBadRequest(
        array $httpFixtures,
        User $user,
        Request $request,
        $website,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        $this->assertEquals($expectedRequestUrl, $httpHistory->getLastRequest()->getUrl());
    }

    /**
     * @return array
     */
    public function indexActionBadRequestDataProvider()
    {
        return [
            'website mismatch' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => '/http://example.com//1/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Ffoo.example.com%2F/1/',
            ],
            'incorrect state' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrls' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param Twig_Environment $twig
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(array $httpFixtures, array $testValues, Twig_Environment $twig)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);
        $this->setTwigOnController($twig, $indexController);

        $response = $indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'no remote tasks retrieved' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3,]),
                ],
                'testValues' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(0, $parameters['completion_percent']);
                            $this->assertInternalType('array', $parameters['website']);

                            /* @var Test $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(Test::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());

                            $this->assertEquals(0, $parameters['local_task_count']);
                            $this->assertEquals(12, $parameters['remote_task_count']);
                            $this->assertEquals(12, $parameters['remaining_tasks_to_retrieve_count']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'some remote tasks retrieved' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_TASKS => [
                        [
                            TaskFactory::KEY_TASK_ID => 1,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                        ],
                        [
                            TaskFactory::KEY_TASK_ID => 2,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                        ],
                        [
                            TaskFactory::KEY_TASK_ID => 3,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
                        ],
                        [
                            TaskFactory::KEY_TASK_ID => 4,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_LINK_INTEGRITY,
                        ],
                    ],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(33, $parameters['completion_percent']);
                            $this->assertInternalType('array', $parameters['website']);

                            /* @var Test $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(Test::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());

                            $this->assertEquals(4, $parameters['local_task_count']);
                            $this->assertEquals(12, $parameters['remote_task_count']);
                            $this->assertEquals(8, $parameters['remaining_tasks_to_retrieve_count']);

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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3,]),
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $indexController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

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
                'completion_percent',
                'website',
                'test',
                'local_task_count',
                'remote_task_count',
                'remaining_tasks_to_retrieve_count',
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
