<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\Account;

use App\Controller\View\User\Account\CardController;
use App\Model\Team\Team;
use App\Model\User\Summary as UserSummary;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class CardControllerTest extends AbstractAccountControllerTest
{
    const VIEW_NAME = 'user-account-card.html.twig';
    const ROUTE_NAME = 'view_user_account_card';
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
            'in' => true,
            'has_invite' => false,
        ],
        'stripe_customer' => [
            'id' => 'cus_aaaaaaaaaaaaa0',
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
                    HttpResponseFactory::createSuccessResponse()
                ],
                'routeName' => self::ROUTE_NAME,
                'expectedRedirectUrl' => '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2NhcmQifQ%3D%3D',
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

    public function testIndexActionInTeamNotLeader()
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures([
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
                'members' => [
                    self::USER_EMAIL,
                ],
            ]),
        ]);

        /* @var CardController $cardController */
        $cardController = self::$container->get(CardController::class);

        /* @var RedirectResponse $response */
        $response = $cardController->indexAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/account/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(array $httpFixtures, Twig_Environment $twig)
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var CardController $cardController */
        $cardController = self::$container->get(CardController::class);
        $this->setTwigOnController($twig, $cardController);

        $response = $cardController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'not in team' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'is in team, is leader' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => 'user@example.com',
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

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

        $this->assertIsString($parameters['stripe_publishable_key']);
        $this->assertEquals('Agency', $parameters['plan_presentation_name']);
        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
        $this->assertIsArray($parameters['countries']);

        $this->assertIsInt($parameters['expiry_year_start']);
        $this->assertIsInt($parameters['expiry_year_end']);
        $this->assertEquals(
            CardController::CARD_EXPIRY_DATE_YEAR_RANGE,
            $parameters['expiry_year_end'] - $parameters['expiry_year_start']
        );
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'stripe_publishable_key',
            'plan_presentation_name',
            'user_summary',
            'countries',
            'expiry_year_start',
            'expiry_year_end',
        ];

        $keys = array_keys($parameters);
        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $keys);
        }
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
