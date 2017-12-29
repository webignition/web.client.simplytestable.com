<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class TeamControllerInviteMemberActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_invitemember';
    const USER_USERNAME = 'user@example.com';
    const INVITEE_EMAIL = 'invitee@example.com';
    const TEAM_NAME = 'Team Name';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/team/';

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = new User(self::USER_USERNAME);

        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($this->user);
    }

    /**
     * {@inheritdoc}
     */
    public function postRequestPublicUserDataProvider()
    {
        return [
            'default' => [
                'routeName' => self::ROUTE_NAME,
            ],
        ];
    }

    public function testInviteMemberActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode($inviteData)),
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
            self::INVITEE_EMAIL
        ));

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($this->user))
        );

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'email' => self::INVITEE_EMAIL,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider inviteMemberActionBadRequestDataProvider
     *
     * @param Request $request
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testInviteMemberActionBadRequest(Request $request, array $expectedFlashBagValues)
    {
        $session = $this->container->get('session');

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->teamController->inviteMemberAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function inviteMemberActionBadRequestDataProvider()
    {
        return [
            'empty invitee' => [
                'request' => new Request(),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                        TeamController::FLASH_BAG_KEY_INVITEE => '',
                    ],
                ],
            ],
            'invalid invitee' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                        TeamController::FLASH_BAG_KEY_INVITEE => 'foo',
                    ],
                ],
            ],
            'invite self' => [
                'request' => new Request([], [
                    'email' => self::USER_USERNAME,
                ]),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_SELF_INVITE,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider inviteMemberActionGetInviteFailureDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testInviteMemberActionGetInviteFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $session = $this->container->get('session');

        $this->container->set('request', new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $this->setHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->teamController->inviteMemberAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function inviteMemberActionGetInviteFailureDataProvider()
    {
        return [
            'invitee is a team leader' => [
                'httpFixtures' => [
                    Response::fromMessage(sprintf(
                        "HTTP/1.1 400 Bad Request\nX-TeamInviteGet-Error-Code:%s\nX-TeamInviteGet-Error-Message:%s",
                        2,
                        'Invitee is a team leader'
                    )),
                ],
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'invitee is already on a team' => [
                'httpFixtures' => [
                    Response::fromMessage(sprintf(
                        "HTTP/1.1 400 Bad Request\nX-TeamInviteGet-Error-Code:%s\nX-TeamInviteGet-Error-Message:%s",
                        3,
                        'Invitee is on a team'
                    )),
                ],
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_ALREADY_ON_A_TEAM,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'invitee has a premium plan' => [
                'httpFixtures' => [
                    Response::fromMessage(sprintf(
                        "HTTP/1.1 400 Bad Request\nX-TeamInviteGet-Error-Code:%s\nX-TeamInviteGet-Error-Message:%s",
                        11,
                        'Invitee has a premium plan'
                    )),
                ],
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_HAS_A_PREMIUM_PLAN,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider inviteMemberActionSendInviteFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testInviteMemberActionSendInviteFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $this->container->set('request', new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode($inviteData)),
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->teamController->inviteMemberAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function inviteMemberActionSendInviteFailureDataProvider()
    {
        return [
            'Postmark not allowed to send' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
                    self::INVITEE_EMAIL,
                    [
                        'ErrorCode' => 405,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark inactive recipient' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
                    self::INVITEE_EMAIL,
                    [
                        'ErrorCode' => 406,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark invalid email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
                    self::INVITEE_EMAIL,
                    [
                        'ErrorCode' => 300,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark unknown error' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInvitePostmarkMessage(
                    self::INVITEE_EMAIL,
                    [
                        'ErrorCode' => 303,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider inviteMemberActionSuccessDataProvider
     *
     * @param Response[] $httpFixtures
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testInviteMemberActionSuccess(
        array $httpFixtures,
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $this->container->set('request', new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setHttpFixtures(array_merge(
            [
                Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode($inviteData)),
            ],
            $httpFixtures
        ));

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->teamController->inviteMemberAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function inviteMemberActionSuccessDataProvider()
    {
        return [
            'user is enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
                    self::INVITEE_EMAIL
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_SUCCESS,
                        TeamController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'user not enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
                    self::INVITEE_EMAIL
                ),
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_SUCCESS,
                        TeamController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }
}
