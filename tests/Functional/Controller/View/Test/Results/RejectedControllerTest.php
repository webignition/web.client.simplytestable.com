<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\RejectedController;
use App\Entity\Task\Task;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;
use App\Model\User\Summary as UserSummary;
use App\Services\TestRetriever;
use App\Services\UserManager;
use App\Tests\Factory\TestModelFactory;
use App\Tests\Services\SymfonyRequestFactory;
use Mockery\MockInterface;
use Symfony\Component\DomCrawler\Crawler;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class RejectedControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results-rejected.html.twig';
    const ROUTE_NAME = 'view_test_results_rejected';
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
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_REJECTED,
        'task_type_options' => [],
        'task_count' => 12,
        'rejection' => [
            'reason' => 'foo',
        ],
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_REJECTED,
        'type' => TestModel::TYPE_FULL_SITE,
        'taskTypes' => [
            Task::TYPE_HTML_VALIDATION,
        ],
        'rejection' => [
            'reason' => 'foo',
        ],
    ];

    private $userData = [
        'email' => self::USER_EMAIL,
        'user_plan' => [
            'plan' => [
                'name' => 'personal',
                'is_premium' => true,
            ],
            'start_trial_period' => 30,
        ],
        'plan_constraints' => [
            'urls_per_job' => 10,
            'credits' => [
                'limit' => 5000,
                'used' => 5001,
            ],
        ],
        'team_summary' => [
            'in' => false,
            'has_invite' => false,
        ],
        'stripe_customer' => [
            'id' => 'cus_aaaaaaaaaaaaa0',
            'subscription' => [
                'plan' => [
                    'currency' => 'gbp',
                ],
            ],
        ],
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
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
        $this->assertRegExp(
            '/\/signin\/\?redirect=.+/',
            $response->getTargetUrl()
        );
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
     * @dataProvider indexActionGetRequestDataProvider
     */
    public function testIndexActionGetRequest(
        array $remoteTestData,
        array $userData,
        array $expectedLeadContentContains
    ) {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($remoteTestData),
            HttpResponseFactory::createJsonResponse($userData),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());

        $crawler = new Crawler($response->getContent());
        $leadContent = $crawler->filter('.lead');

        $leadContent->each(function (Crawler $content, $index) use ($expectedLeadContentContains) {
            $expectedContentContainsCollection = $expectedLeadContentContains[$index];

            foreach ($expectedContentContainsCollection as $expectedContentContains) {
                $this->assertContains($expectedContentContains, $content->html());
            }
        });

        $this->assertEquals(
            [
                'http://null/user/public/authenticate/',
                'http://null/job/1/is-authorised/',
                'http://null/job/1/',
                'http://null/user/public/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    public function indexActionGetRequestDataProvider(): array
    {
        return [
            'personal credit limit reached; agency plan available' => [
                'remoteTestData' => array_merge($this->remoteTestData, [
                    'rejection' => [
                        'reason' => 'plan-constraint-limit-reached',
                        'constraint' => [
                            'name' => 'credits_per_month',
                            'limit' => 5000,
                        ]
                    ],
                ]),
                'userData' => array_merge($this->userData, []),
                'expectedLeadContentContains' => [
                    [
                        'limit of <strong>5,000</strong>',
                    ],
                    [
                        'from the <strong>personal</strong>',
                        'to the  <strong>agency</strong>',
                    ]
                ],
            ],
            'agency credit limit reached; business plan available' => [
                'remoteTestData' => array_merge($this->remoteTestData, [
                    'rejection' => [
                        'reason' => 'plan-constraint-limit-reached',
                        'constraint' => [
                            'name' => 'credits_per_month',
                            'limit' => 20000,
                        ]
                    ],
                ]),
                'userData' => array_merge($this->userData, [
                    'user_plan' => [
                        'plan' => [
                            'name' => 'agency',
                            'is_premium' => true,
                        ],
                        'start_trial_period' => 30,
                    ],
                    'plan_constraints' => [
                        'urls_per_job' => 10,
                        'credits' => [
                            'limit' => 20000,
                            'used' => 20001,
                        ],
                    ],
                ]),
                'expectedLeadContentContains' => [
                    [
                        'limit of <strong>20,000</strong>',
                    ],
                    [
                        'from the <strong>agency</strong>',
                        'to the  <strong>business</strong>',
                    ]
                ],
            ],
            'business credit limit reached; enterprise plan available' => [
                'remoteTestData' => array_merge($this->remoteTestData, [
                    'rejection' => [
                        'reason' => 'plan-constraint-limit-reached',
                        'constraint' => [
                            'name' => 'credits_per_month',
                            'limit' => 100000,
                        ]
                    ],
                ]),
                'userData' => array_merge($this->userData, [
                    'user_plan' => [
                        'plan' => [
                            'name' => 'business',
                            'is_premium' => true,
                        ],
                        'start_trial_period' => 30,
                    ],
                    'plan_constraints' => [
                        'urls_per_job' => 10,
                        'credits' => [
                            'limit' => 100000,
                            'used' => 100001,
                        ],
                    ],
                ]),
                'expectedLeadContentContains' => [
                    [
                        'limit of <strong>100,000</strong>',
                    ],
                    [
                        'You\'re on our largest standard plan. We can\'t offer you any direct upgrade options',
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     */
    public function testIndexActionBadRequest(array $testModelProperties, string $expectedRedirectUrl)
    {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($rejectedController, $testRetriever);

        /* @var RedirectResponse $response */
        $response = $rejectedController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionBadRequestDataProvider(): array
    {
        return [
            'website mismatch' => [
                'testModelProperties' => [
                    'website' => 'http://foo.example.com/',
                ],
                'expectedRedirectUrl' => '/http://foo.example.com//1/results/rejected/',
            ],
            'incorrect state' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_IN_PROGRESS,
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $testModelProperties,
        array $httpFixtures,
        Twig_Environment $twig
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($rejectedController, $testRetriever);
        $this->setTwigOnController($twig, $rejectedController);

        $response = $rejectedController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'unroutable' => [
                'testModelProperties' => [
                    'rejection' => [
                        'reason' => 'unroutable',
                    ],
                ],
                'httpFixtures' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertEquals(
                                [
                                    'user',
                                    'is_logged_in',
                                    'website',
                                    'test',
                                    'plans',
                                ],
                                array_keys($parameters)
                            );

                            $this->assertIsArray($parameters['website']);
                            $this->assertIsArray($parameters['plans']);

                            /* @var DecoratedTest $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(DecoratedTest::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, $test->getWebsite());

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'credit limit reached' => [
                'testModelProperties' => [
                    'rejection' => [
                        'reason' => 'plan-constraint-limit-reached',
                        'constraint' => [
                            'name' => 'credits_per_month',
                            'limit' => 10,
                        ],
                    ],
                ],
                'httpFixtures' => [
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
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertEquals(
                                [
                                    'user',
                                    'is_logged_in',
                                    'website',
                                    'test',
                                    'plans',
                                    'userSummary',
                                ],
                                array_keys($parameters)
                            );

                            $this->assertIsArray($parameters['website']);
                            $this->assertIsArray($parameters['plans']);

                            /* @var DecoratedTest $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(DecoratedTest::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, $test->getWebsite());

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
        $testModel = TestModelFactory::create($this->testModelProperties);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($rejectedController, $testRetriever);

        $response = $rejectedController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

        $newResponse = $rejectedController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
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
