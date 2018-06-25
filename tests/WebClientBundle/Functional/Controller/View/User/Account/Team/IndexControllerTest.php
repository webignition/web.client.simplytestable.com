<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamInviteController;
use SimplyTestable\WebClientBundle\Controller\View\User\Account\Team\IndexController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class IndexControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/Account/Team/Index:index.html.twig';
    const ROUTE_NAME = 'view_user_account_team_index_index';

    const USER_EMAIL = 'user@example.com';

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
            'in' => true,
            'has_invite' => false,
        ],
        'stripe_customer' => [
            'id' => 'cus_aaaaaaaaaaaaa0',
        ],
    ];

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

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
                    HttpResponseFactory::createNotFoundResponse()
                ],
                'expectedRedirectUrl' => '/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedRedirectUrl' =>
                    '/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNl'
                    .'cl9hY2NvdW50X3RlYW1faW5kZXhfaW5kZXgifQ%3D%3D'
            ],
        ];
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $userManager = $this->container->get(UserManager::class);
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
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param array $flashBagValues
     * @param Twig_Environment $twig
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagValues,
        Twig_Environment $twig
    ) {
        $userManager = $this->container->get(UserManager::class);

        $session = $this->container->get('session');

        $user = new User(self::USER_EMAIL);

        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);
        $this->setTwigOnController($twig, $indexController);

        $response = $indexController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
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
                'flashBagValues' => [],
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
                'flashBagValues' => [
                    TeamController::FLASH_BAG_CREATE_ERROR_KEY =>
                        TeamController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(
                                TeamController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
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
                'flashBagValues' => [
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
                'flashBagValues' => [
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
                'flashBagValues' => [],
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
                'flashBagValues' => [],
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
                'flashBagValues' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

                            $this->assertArrayHasKey('invites', $parameters);
                            $this->assertInternalType('array', $parameters['invites']);
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
                'flashBagValues' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team_create_error', $parameters);

                            $this->assertArrayHasKey('invites', $parameters);
                            $this->assertInternalType('array', $parameters['invites']);
                            $this->assertInstanceOf(Invite::class, $parameters['invites'][0]);

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

        $this->assertEquals('Agency', $parameters['plan_presentation_name']);
        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
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
                'plan_presentation_name',
                'user_summary',
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
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
