<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\Progress;

use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\Progress\IndexController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\CssValidationTestConfiguration;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\JsStaticAnalysisTestConfiguration;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapterFactory;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class IndexControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Progress/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_progress_index_index';

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
        'state' => Test::STATE_IN_PROGRESS,
        'task_type_options' => [],
    ];

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
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
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User(self::USER_EMAIL));

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

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
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionHttpRedirect(
        array $httpFixtures,
        User $user,
        $website,
        $testId,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction(new Request(), $website, $testId);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        $this->assertEquals($expectedRequestUrl, $httpHistory->getLastRequest()->getUrl());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionJsRedirect(
        array $httpFixtures,
        User $user,
        $website,
        $testId,
        $expectedRedirectUrl,
        $expectedRequestUrl
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $indexController = $this->container->get(IndexController::class);

        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = $indexController->indexAction($request, $website, $testId);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($expectedRedirectUrl, $responseData->this_url);
        $this->assertEquals($expectedRequestUrl, $httpHistory->getLastRequest()->getUrl());
    }

    /**
     * @return array
     */
    public function indexActionRedirectDataProvider()
    {
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User('user@example.com');

        return [
            'remote test website not match request website' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'user' => $publicUser,
                'website' => 'foo',
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/foo/1/',
            ],
            'finished test; state=completed' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_COMPLETED,
                    ])),
                ],
                'user' => $publicUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'finished test; state=failed_no_sitemap; public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => $publicUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
            'finished test; state=failed_no_sitemap; private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => $privateUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/re-test/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderTextHtmlDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Twig_Environment $twig
     */
    public function testIndexActionRenderTextHtml(
        array $httpFixtures,
        User $user,
        Twig_Environment $twig
    ) {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $indexController = new IndexController(
            $this->container->get('router'),
            $twig,
            $this->container->get(DefaultViewParameters::class),
            $this->container->get(CacheValidatorService::class),
            $this->container->get(UrlViewValuesService::class),
            $this->container->get(UserManager::class),
            $this->container->get('session'),
            $this->container->get(TestService::class),
            $this->container->get(RemoteTestService::class),
            $this->container->get(TaskTypeService::class),
            $this->container->get(RequestAdapterFactory::class),
            $this->container->get(CssValidationTestConfiguration::class),
            $this->container->get(JsStaticAnalysisTestConfiguration::class)
        );

        $response = $indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderTextHtmlDataProvider()
    {
        return [
            'public user, in-progress, 79% done' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                        'task_count' => 100,
                        'url_count' => 50,
                        'task_count_by_state' => [
                            'completed' => 79,
                        ],
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertStateLabel($parameters, '50 urls, 100 tests; 79% done');
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);
                            $this->assertInternalType('array', $parameters['test_options']);
                            $this->assertTrue($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, queued, 0% done' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_QUEUED,
                        'task_count' => 44,
                        'url_count' => 11,
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertStateLabel(
                                $parameters,
                                '11 urls, 44 tests; waiting for first test to begin'
                            );
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);
                            $this->assertInternalType('array', $parameters['test_options']);
                            $this->assertTrue($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, crawling' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_CRAWLING,
                        'task_count' => 44,
                        'url_count' => 11,
                        'user' => 'user@example.com',
                        'crawl' => [
                            'processed_url_count' => 10,
                            'discovered_url_count' => 30,
                            'limit' => 250,
                        ],
                    ])),
                ],
                'user' => new User('user@example.com'),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertInternalType('array', $parameters['website']);
                            $this->assertStateLabel(
                                $parameters,
                                'Finding URLs to test: 10 pages examined, 30 of 250 found'
                            );
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                                'js-static-analysis',
                                'link-integrity',
                            ]);
                            $this->assertInternalType('array', $parameters['test_options']);
                            $this->assertFalse($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderApplicationJsonDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param string $expectedStateLabel
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRenderApplicationJson(
        array $httpFixtures,
        User $user,
        $expectedStateLabel
    ) {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $indexController = $this->container->get(IndexController::class);

        $request = new Request();
        $request->headers->set('accept', 'application/json');

        $response = $indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('application/json', $response->headers->get('content-type'));

        $responseData = json_decode($response->getContent(), true);

        $this->assertInternalType('array', $responseData);
        $this->assertEquals([
            'test',
            'state_label',
            'remote_test',
            'this_url',
        ], array_keys($responseData));

        $this->assertInternalType('array', $responseData['test']);
        $this->assertEquals(self::TEST_ID, $responseData['test']['test_id']);

        $this->assertInternalType('array', $responseData['remote_test']);
        $this->assertEquals(self::TEST_ID, $responseData['remote_test']['id']);
        $this->assertEquals(self::WEBSITE, $responseData['remote_test']['website']);

        $this->assertEquals('http://localhost/http://example.com//1/progress/', $responseData['this_url']);
        $this->assertEquals($expectedStateLabel, $responseData['state_label']);
    }

    /**
     * @return array
     */
    public function indexActionRenderApplicationJsonDataProvider()
    {
        return [
            'public user, in-progress, 79% done' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                        'task_count' => 100,
                        'url_count' => 50,
                        'task_count_by_state' => [
                            'completed' => 79,
                        ],
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'expectedStateLabel' => '50 urls, 100 tests; 79% done',
            ],
            'public user, queued, 0% done' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_QUEUED,
                        'task_count' => 44,
                        'url_count' => 11,
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => SystemUserService::getPublicUser(),
                'expectedStateLabel' => '11 urls, 44 tests; waiting for first test to begin',
            ],
            'private user, crawling' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_CRAWLING,
                        'task_count' => 44,
                        'url_count' => 11,
                        'user' => 'user@example.com',
                        'crawl' => [
                            'processed_url_count' => 10,
                            'discovered_url_count' => 30,
                            'limit' => 250,
                        ],
                    ])),
                ],
                'user' => new User('user@example.com'),
                'expectedStateLabel' => 'Finding URLs to test: 10 pages examined, 30 of 250 found',
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);
        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction(
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
        $newResponse = $indexController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID
        );

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
                'test',
                'state_label',
                'remote_test',
                'website',
                'available_task_types',
                'task_types',
                'test_options',
                'is_public_user_test',
                'css_validation_ignore_common_cdns',
                'js_static_analysis_ignore_common_cdns',
                'default_css_validation_options',
                'default_js_static_analysis_options',
            ],
            array_keys($parameters)
        );
    }

    /**
     * @param array $parameters
     */
    private function assertTestAndRemoteTest(array $parameters)
    {
        $this->assertInstanceOf(Test::class, $parameters['test']);

        /* @var Test $test */
        $test = $parameters['test'];
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());

        $this->assertInstanceOf(RemoteTest::class, $parameters['remote_test']);

        /* @var RemoteTest $remoteTest */
        $remoteTest = $parameters['remote_test'];
        $this->assertEquals(self::TEST_ID, $remoteTest->getId());
        $this->assertEquals(self::WEBSITE, $remoteTest->getWebsite());
    }

    /**
     * @param array $parameters
     * @param string $expectedStateLabel
     */
    private function assertStateLabel(array $parameters, $expectedStateLabel)
    {
        $this->assertInternalType('string', $parameters['state_label']);
        $this->assertEquals($expectedStateLabel, $parameters['state_label']);
    }

    /**
     * @param array $parameters
     * @param array $expectedTaskTypeKeys
     */
    private function assertAvailableTaskTypes(array $parameters, $expectedTaskTypeKeys)
    {
        $this->assertInternalType('array', $parameters['available_task_types']);
        $this->assertEquals($expectedTaskTypeKeys, array_keys($parameters['available_task_types']));
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
