<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Progress;

use SimplyTestable\WebClientBundle\Controller\View\Test\Progress\IndexController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends BaseSimplyTestableTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Progress/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_progress_index_index';

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
        'state' => Test::STATE_IN_PROGRESS,
        'task_type_options' => [],
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
        $this->setHttpFixtures([
            HttpResponseFactory::create(404),
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

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
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

        $this->assertRegExp(
            '/http:\/\/localhost\/signin\/\?redirect=.+/',
            $response->getTargetUrl()
        );
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
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

    /**
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws WebResourceException
     */
    public function testIndexActionHttpRedirect(
        array $httpFixtures,
        User $user,
        $website,
        $testId,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        $httpHistory = new HttpHistory($this->container->get('simplytestable.services.httpclientservice'));

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction(new Request(), $website, $testId);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());
        }
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param string $website
     * @param int $testId
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws WebResourceException
     */
    public function testIndexActionJsRedirect(
        array $httpFixtures,
        User $user,
        $website,
        $testId,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        $httpHistory = new HttpHistory($this->container->get('simplytestable.services.httpclientservice'));

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $this->indexController->setContainer($this->container);

        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = $this->indexController->indexAction($request, $website, $testId);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($expectedRedirectUrl, $responseData->this_url);

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());
        }
    }

    /**
     * @return array
     */
    public function indexActionRedirectDataProvider()
    {
        $publicUser = new User(UserService::PUBLIC_USER_USERNAME);
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
                'expectedRequestUrls' => [
                    'http://null/job/foo/1/',
                ],
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
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
            'finished test; state=failed_no_sitemap; public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => $publicUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/results/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
            'finished test; state=failed_no_sitemap; private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_FAILED_NO_SITEMAP,
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => $privateUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/re-test/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionTextHtmlResponseDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param EngineInterface $templatingEngine
     *
     * @throws WebResourceException
     */
    public function testIndexActionTextHtmlResponse(
        array $httpFixtures,
        User $user,
        EngineInterface $templatingEngine
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');

        $userService->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'simplytestable.services.testservice',
                'simplytestable.services.remotetestservice',
                'simplytestable.services.userservice',
                'simplytestable.services.cacheableresponseservice',
                'simplytestable.services.urlviewvalues',
                'serializer',
                'simplytestable.services.tasktypeservice',
                'simplytestable.services.testoptions.adapter.factory',
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
                'css-validation-ignore-common-cdns',
                'js-static-analysis-ignore-common-cdns',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionTextHtmlResponseDataProvider()
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
     * @dataProvider indexActionApplicationJsonResponseDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     *
     * @throws WebResourceException
     */
    public function testIndexActionApplicationJsonResponse(
        array $httpFixtures,
        User $user,
        $expectedStateLabel
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');

        $userService->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $this->indexController->setContainer($this->container);

        $request = new Request();
        $request->headers->set('accept', 'application/json');

        $response = $this->indexController->indexAction($request, self::WEBSITE, self::TEST_ID);
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
    public function indexActionApplicationJsonResponseDataProvider()
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
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'expectedStateLabel' => '50 urls, 100 tests; 79% done',
            ],
            'public user, queued, 0% done' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_QUEUED,
                        'task_count' => 44,
                        'url_count' => 11,
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
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

    private function assertAvailableTaskTypes(array $parameters, $expectedTaskTypeKeys)
    {
        $this->assertInternalType('array', $parameters['available_task_types']);
        $this->assertEquals($expectedTaskTypeKeys, array_keys($parameters['available_task_types']));
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