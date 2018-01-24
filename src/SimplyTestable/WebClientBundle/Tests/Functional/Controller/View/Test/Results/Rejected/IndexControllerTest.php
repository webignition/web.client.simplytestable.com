<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Rejected;

use SimplyTestable\WebClientBundle\Controller\View\Test\Results\Rejected\IndexController;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends BaseSimplyTestableTestCase
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

        $this->assertInstanceOf(RedirectResponse::class, $response);
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
     * @dataProvider indexActionBadRequestDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param $website
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws WebResourceException
     */
    public function testIndexActionBadRequest(
        array $httpFixtures,
        Request $request,
        $website,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $httpHistory = new HttpHistory($this->container->get('simplytestable.services.httpclientservice'));

        $this->setHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());
        }
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
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Ffoo.example.com%2F/1/',
                ],
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
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param EngineInterface $templatingEngine
     *
     * @throws WebResourceException
     */
    public function testIndexActionRender(array $httpFixtures, EngineInterface $templatingEngine)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser(new User(self::USER_EMAIL));

        $this->setHttpFixtures($httpFixtures);

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
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
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
                                    'public_site',
                                    'external_links',
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
                                    'public_site',
                                    'external_links',
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
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        $this->container->set('request', $request);
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
     * {@inheritdoc}
     */
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}