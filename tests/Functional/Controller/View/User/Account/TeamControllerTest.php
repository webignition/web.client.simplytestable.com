<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\Account;

use App\Controller\Action\User\Account\TeamController as TeamActionController;
use App\Controller\Action\User\Account\TeamInviteController;
use App\Controller\View\User\Account\TeamController;
use App\Model\Team\Invite;
use App\Model\Team\Team;
use App\Model\User\Summary as UserSummary;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class TeamControllerTest extends AbstractAccountControllerTest
{
    const VIEW_NAME = 'user-account-team.html.twig';
    const ROUTE_NAME = 'view_user_account_team';
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
                    HttpResponseFactory::createNotFoundResponse()
                ],
                'routeName' => self::ROUTE_NAME,
                'expectedRedirectUrl' => '/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'routeName' => self::ROUTE_NAME,
                'expectedRedirectUrl' =>'/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X3RlYW0ifQ%3D%3D',
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

        /* @var TeamController $teamController */
        $teamController = self::$container->get(TeamController::class);
        $this->setTwigOnController($twig, $teamController);

        $response = $teamController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'not in team, no invites, no flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'team create error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagMessages' => [
                    TeamActionController::FLASH_BAG_CREATE_ERROR_KEY => [
                        TeamActionController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
                    ],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(
                                TeamActionController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
                                $parameters['team_create_error']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'error getting team invite' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagMessages' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS =>
                            TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE =>
                            'invitee@example.com',
                    ],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team_invite_get', $parameters);
                            $this->assertEquals(
                                [
                                    TeamInviteController::FLASH_BAG_KEY_STATUS =>
                                        TeamInviteController::FLASH_BAG_STATUS_ERROR,
                                    TeamInviteController::FLASH_BAG_KEY_ERROR =>
                                        TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                                    TeamInviteController::FLASH_BAG_KEY_INVITEE =>
                                        'invitee@example.com',
                                ],
                                $parameters['team_invite_get']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'error re-sending team invite' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'flashBagMessages' => [
                    TeamInviteController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => 'invitee@example.com',
                    ],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team_invite_resend', $parameters);
                            $this->assertEquals(
                                [
                                    TeamInviteController::FLASH_BAG_KEY_STATUS =>
                                        TeamInviteController::FLASH_BAG_STATUS_ERROR,
                                    TeamInviteController::FLASH_BAG_KEY_ERROR =>
                                        TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                                    TeamInviteController::FLASH_BAG_KEY_INVITEE => 'invitee@example.com',
                                ],
                                $parameters['team_invite_resend']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'in team, not leader' => [
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
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);
                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'in team, is leader, no invites' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => self::USER_EMAIL,
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

                            $this->assertArrayHasKey('invites', $parameters);
                            $this->assertEmpty($parameters['invites']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'in team, is leader, has invites' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => self::USER_EMAIL,
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'team' => 'Team Name',
                            'user' => 'invitee@example.com',
                            'token' => 'user-invite-token',
                        ],
                    ]),
                ],
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

                            $this->assertArrayHasKey('invites', $parameters);
                            $this->assertIsArray($parameters['invites']);
                            $this->assertInstanceOf(Invite::class, $parameters['invites'][0]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'not team, has invites' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => true,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'team' => 'Team Name',
                            'user' => 'invitee@example.com',
                            'token' => 'user-invite-token',
                        ],
                    ]),
                ],
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            $this->assertArrayHasKey('invites', $parameters);
                            $this->assertIsArray($parameters['invites']);
                            $this->assertInstanceOf(Invite::class, $parameters['invites'][0]);

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

        $this->assertEquals('Agency', $parameters['plan_presentation_name']);
        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'plan_presentation_name',
            'user_summary',
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
