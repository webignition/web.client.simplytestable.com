<?php

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\RejectedController;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use App\Model\User\Summary as UserSummary;
use App\Services\UserManager;
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

    /**
     * @var array
     */
    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

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
     * @var array
     */
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

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Not authorised', $response->getContent());
    }

    /**
     * @dataProvider indexActionGetRequestDataProvider
     *
     * @param array $remoteTestData
     * @param array $userData
     * @param array $expectedLeadContentContains
     */
    public function testIndexActionGetRequestFoo(
        array $remoteTestData,
        array $userData,
        array $expectedLeadContentContains
    ) {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
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
    }

    /**
     * @return array
     */
    public function indexActionGetRequestDataProvider()
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
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);

        /* @var RedirectResponse $response */
        $response = $rejectedController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
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
                'expectedRedirectUrl' => '/http://example.com//1/',
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
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param Twig_Environment $twig
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(array $httpFixtures, Twig_Environment $twig)
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);
        $this->setTwigOnController($twig, $rejectedController);

        $response = $rejectedController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
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
                'twig' => MockFactory::createTwig([
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
                'twig' => MockFactory::createTwig([
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var RejectedController $rejectedController */
        $rejectedController = self::$container->get(RejectedController::class);

        $response = $rejectedController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $rejectedController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
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
