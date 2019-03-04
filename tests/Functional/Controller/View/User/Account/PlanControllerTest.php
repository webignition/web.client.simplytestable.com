<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\Account;

use App\Controller\View\User\Account\PlanController;
use App\Model\Team\Team;
use App\Model\User\Plan;
use App\Model\User\Summary as UserSummary;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class PlanControllerTest extends AbstractAccountControllerTest
{
    const VIEW_NAME = 'user-account-plan.html.twig';
    const ROUTE_NAME = 'view_user_account_plan';
    const USER_EMAIL = 'user@example.com';

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

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function invalidUserGetRequestDataProvider(): array
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'routeName' => self::ROUTE_NAME,
                'expectedRedirectUrl' => '/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => self::ROUTE_NAME,
                'expectedRedirectUrl' => '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X3BsYW4ifQ%3D%3D',
            ],
        ];
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser(new User(self::USER_EMAIL));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                'team_summary' => [
                    'in' => false,
                    'has_invite' => false,
                ],
            ])),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testIndexActionCustomPlan()
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures([
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

        /* @var PlanController $planController */
        $planController = self::$container->get(PlanController::class);

        /* @var RedirectResponse $response */
        $response = $planController->indexAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(array $httpFixtures, array $flashBagMessages, Twig_Environment $twig)
    {
        $userManager = self::$container->get(UserManager::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $flashBag->setAll($flashBagMessages);

        /* @var PlanController $planController */
        $planController = self::$container->get(PlanController::class);
        $this->setTwigOnController($twig, $planController);

        $response = $planController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
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
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('plan_subscribe_error', $parameters);
                            $this->assertArrayNotHasKey('plan_subscribe_success', $parameters);

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
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('plan_subscribe_error', $parameters);
                            $this->assertArrayNotHasKey('plan_subscribe_success', $parameters);

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
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);
                            $this->assertArrayNotHasKey('plan_subscribe_error', $parameters);
                            $this->assertArrayNotHasKey('plan_subscribe_success', $parameters);

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
                    'plan_subscribe_error' => [403],
                    'plan_subscribe_success' => ['success'],
                ],
                'twig' => MockFactory::createTwig([
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

    private function assertCommonViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
        $this->assertEquals('Agency', $parameters['plan_presentation_name']);

        $this->assertIsArray($parameters['plans']);
        foreach ($parameters['plans'] as $plan) {
            $this->assertInstanceOf(Plan::class, $plan);
        }

        $this->assertIsArray($parameters['currency_map']);
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'user_summary',
                'plan_presentation_name',
                'plans',
                'currency_map',
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
