<?php

namespace App\Tests\Functional\Controller\Action\User\Account\TeamInvite;

use App\Services\Mailer;
use Psr\Http\Message\ResponseInterface;
use App\Controller\Action\User\Account\TeamInviteController;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class TeamInviteControllerInviteMemberActionTest extends AbstractTeamInviteControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_invitemember';
    const USER_USERNAME = 'user@example.com';
    const INVITEE_EMAIL = 'invitee@example.com';
    const INVITE_TOKEN = 'invite-token';
    const TEAM_NAME = 'Team Name';
    const EXPECTED_REDIRECT_URL = '/account/team/';

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

        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->user);
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
        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => self::INVITE_TOKEN,
        ];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($inviteData),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME),
            [
                'email' => self::INVITEE_EMAIL,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @dataProvider inviteMemberActionBadRequestDataProvider
     *
     * @param Request $request
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     * @throws InvalidAdminCredentialsException
     */
    public function testInviteMemberActionBadRequest(Request $request, array $expectedFlashBagValues)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $response = $this->callInviteMemberAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
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
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => '',
                    ],
                ],
            ],
            'invalid invitee' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_INVALID,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => 'foo',
                    ],
                ],
            ],
            'invite self' => [
                'request' => new Request([], [
                    'email' => self::USER_USERNAME,
                ]),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_SELF_INVITE,
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
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testInviteMemberActionGetInviteFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->callInviteMemberAction(new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
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
                    HttpResponseFactory::createBadRequestResponse([
                        'X-TeamInviteGet-Error-Code' => 2,
                        'X-TeamInviteGet-Error-Message' => 'Invitee is a team leader',
                    ]),
                ],
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'invitee is already on a team' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-TeamInviteGet-Error-Code' => 3,
                        'X-TeamInviteGet-Error-Message' => 'Invitee is on a team',
                    ]),
                ],
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_ALREADY_ON_A_TEAM,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'invitee has a premium plan' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-TeamInviteGet-Error-Code' => 11,
                        'X-TeamInviteGet-Error-Message' => 'Invitee has a premium plan',
                    ]),
                ],
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_HAS_A_PREMIUM_PLAN,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'unknown error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-TeamInviteGet-Error-Code' => 999,
                        'X-TeamInviteGet-Error-Message' => 'Unknown error',
                    ]),
                ],
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_UNKNOWN,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider inviteMemberActionSendInviteFailureDataProvider
     *
     * @param ResponseInterface $postmarkHttpResponse
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testInviteMemberActionSendInviteFailure(
        ResponseInterface $postmarkHttpResponse,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => self::INVITE_TOKEN,
        ];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($inviteData),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            $postmarkHttpResponse,
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $response = $this->callInviteMemberAction(new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function inviteMemberActionSendInviteFailureDataProvider()
    {
        return [
            'Postmark not allowed to send' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark inactive recipient' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(406),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark invalid email' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(300),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'Postmark unknown error' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(303),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider inviteMemberActionSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedPostmarkMessageContains
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testInviteMemberActionSuccess(
        array $httpFixtures,
        $expectedPostmarkMessageContains,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => self::INVITE_TOKEN,
        ];

        $this->httpMockHandler->appendFixtures(array_merge(
            [
                HttpResponseFactory::createJsonResponse($inviteData),
            ],
            $httpFixtures,
            [
                PostmarkHttpResponseFactory::createSuccessResponse(),
            ]
        ));

        $response = $this->callInviteMemberAction(new Request([], [
            'email' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());

        $httpHistory = self::$container->get(HttpHistoryContainer::class);
        $lastMessageBody = json_decode($httpHistory->getLastRequest()->getBody()->getContents(), true);

        $this->assertContains($expectedPostmarkMessageContains, $lastMessageBody['TextBody']);
    }

    /**
     * @return array
     */
    public function inviteMemberActionSuccessDataProvider()
    {
        return [
            'user is enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedPostmarkMessageContains' => 'http://localhost/account/team/',
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_SUCCESS,
                        TeamInviteController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'user not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse()
                ],
                'expectedPostmarkMessageContains' => sprintf('http://localhost/signup/invite/%s/', self::INVITE_TOKEN),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_SUCCESS,
                        TeamInviteController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    private function callInviteMemberAction(Request $request)
    {
        return $this->teamInviteController->inviteMemberAction(
            self::$container->get(Mailer::class),
            $request
        );
    }
}
