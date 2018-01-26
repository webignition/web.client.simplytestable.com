<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\View\User\Account\PlanController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Plan;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PlanControllerTest extends BaseSimplyTestableTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/Account/Plan:index.html.twig';
    const ROUTE_NAME = 'view_user_account_plan_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var PlanController
     */
    private $planController;

    /**
     * @var array
     */
    private $userData = [
        'email' => self::USER_EMAIL,
        'user_plan' => [
            'plan' => [
                'name' => 'agency',
                'is_premium' => true,
            ],
            'start_trial_period' => 30,
        ],
        'plan_constraints' => [
            'urls_per_job' => 10,
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

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->planController = new PlanController();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setHttpFixtures($httpFixtures);

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
                    HttpResponseFactory::create(404),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                ],
                'expectedRedirectUrl' =>
                    'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X3BsYW5faW5kZXgifQ%3D%3D'
            ],
        ];
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $user = new User(self::USER_EMAIL);
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                'team_summary' => [
                    'in' => false,
                    'has_invite' => false,
                ],
            ])),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
            $userSerializerService->serializeToString($user)
        ));

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testIndexActionCustomPlan()
    {
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = new User(self::USER_EMAIL);
        $userService->setUser($user);

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                'user_plan' => [
                    'plan' => [
                        'name' => 'agency-custom',
                        'is_premium' => true,
                    ],
                    'start_trial_period' => 30,
                ],
            ])),
        ]);

        $this->planController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->planController->indexAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param array $flashBagValues
     * @param EngineInterface $templatingEngine
     *
     * @throws WebResourceException
     * @throws \Exception
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagValues,
        EngineInterface $templatingEngine
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $session = $this->container->get('session');

        $user = new User(self::USER_EMAIL);

        $userService->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'session',
                'simplytestable.services.userservice',
                'simplytestable.services.teamservice',
                'simplytestable.services.plansservice',
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
                'currency_map',
            ]
        );

        $this->planController->setContainer($container);

        $response = $this->planController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'not in team, no discount, no flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertEmpty($parameters['plan_subscribe_error']);
                            $this->assertEmpty($parameters['plan_subscribe_success']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'not in team, has discount, no flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
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
                            'discount' => [
                                'coupon' => [
                                    'percent_off' => 20,
                                ],
                            ],
                        ],
                    ])),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertEmpty($parameters['plan_subscribe_error']);
                            $this->assertEmpty($parameters['plan_subscribe_success']);

                            /* @var Plan[] $plans */
                            $plans = $parameters['plans'];

                            foreach ($plans as $plan) {
                                $this->assertEquals(0.8, $plan->getPriceModifier());
                            }

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'in team, no discount, no flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => 'leader@example.com',
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);
                            $this->assertEmpty($parameters['plan_subscribe_error']);
                            $this->assertEmpty($parameters['plan_subscribe_success']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'not in team, no discount, has flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagValues' => [
                    'plan_subscribe_error' => 403,
                    'plan_subscribe_success' => 'success',
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertEquals(403, $parameters['plan_subscribe_error']);
                            $this->assertEquals('success', $parameters['plan_subscribe_success']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertCommonViewData($viewName, $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
        $this->assertEquals('Agency', $parameters['plan_presentation_name']);

        $this->assertInternalType('array', $parameters['plans']);
        foreach ($parameters['plans'] as $plan) {
            $this->assertInstanceOf(Plan::class, $plan);
        }

        $this->assertInternalType('array', $parameters['currency_map']);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'public_site',
                'external_links',
                'user_summary',
                'plan_presentation_name',
                'plans',
                'currency_map',
                'plan_subscribe_error',
                'plan_subscribe_success',
            ],
            array_keys($parameters)
        );
    }

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME);
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
