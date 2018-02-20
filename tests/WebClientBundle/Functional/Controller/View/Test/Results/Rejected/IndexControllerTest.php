<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\Results\Rejected;

use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\Results\Rejected\IndexController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Results/Rejected/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_results_rejected_index_index';

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
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_REJECTED,
        'task_type_options' => [],
        'task_count' => 12,
        'rejection' => [
            'reason' => 'foo',
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

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertRegExp(
            '/http:\/\/localhost\/signin\/\?redirect=.+/',
            $response->getTargetUrl()
        );
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userSerializerService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserSerializerService');

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->client->request(
            'GET',
            $this->createRequestUrl()
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
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param $website
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testIndexActionBadRequest(
        array $httpFixtures,
        Request $request,
        $website,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request, $website, self::TEST_ID);
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
                'request' => new Request(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Ffoo.example.com%2F/1/',
            ],
            'incorrect state' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                ],
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(array $httpFixtures, EngineInterface $templatingEngine)
    {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
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
                'simplytestable.services.cachevalidator',
                'simplytestable.services.urlviewvalues',
                'SimplyTestable\WebClientBundle\Services\TaskService',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'plans',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'unroutable' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'rejection' => [
                            'reason' => 'unroutable',
                        ],
                    ])),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertEquals(
                                [
                                    'user',
                                    'is_logged_in',
                                    'website',
                                    'remote_test',
                                    'plans',
                                ],
                                array_keys($parameters)
                            );

                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertInternalType('array', $parameters['plans']);

                            /* @var RemoteTest $remoteTest */
                            $remoteTest = $parameters['remote_test'];
                            $this->assertInstanceOf(RemoteTest::class, $remoteTest);
                            $this->assertEquals(self::TEST_ID, $remoteTest->getId());
                            $this->assertEquals(self::WEBSITE, $remoteTest->getWebsite());

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'credit limit reached' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'rejection' => [
                            'reason' => 'plan-constraint-limit-reached',
                            'constraint' => [
                                'name' => 'credits_per_month',
                                'limit' => 10,
                            ],
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'email' => self::USER_EMAIL,
                        'user_plan' => [
                            'plan' => [
                                'name' => 'basic',
                                'is_premium' => false,
                            ],
                            'start_trial_period' => 30,
                        ],
                        'plan_constraints' => [
                            'urls_per_job' => 10,
                            'credits' => [
                                'limit' => 10,
                                'used' => 10,
                            ],
                        ],
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ]),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertEquals(
                                [
                                    'user',
                                    'is_logged_in',
                                    'website',
                                    'remote_test',
                                    'plans',
                                    'userSummary',
                                ],
                                array_keys($parameters)
                            );

                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertInternalType('array', $parameters['plans']);

                            /* @var RemoteTest $remoteTest */
                            $remoteTest = $parameters['remote_test'];
                            $this->assertInstanceOf(RemoteTest::class, $remoteTest);
                            $this->assertEquals(self::TEST_ID, $remoteTest->getId());
                            $this->assertEquals(self::WEBSITE, $remoteTest->getWebsite());

                            /* @var UserSummary $userSummary */
                            $userSummary = $parameters['userSummary'];
                            $this->assertInstanceOf(UserSummary::class, $userSummary);

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
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);
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
