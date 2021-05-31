<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\User\Account\TeamInvite;

use App\Model\Team\Invite;
use App\Services\Mailer;
use App\Tests\Factory\PostmarkExceptionFactory;
use Postmark\Models\PostmarkException;
use App\Controller\Action\User\Account\TeamInviteController;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;

class TeamInviteControllerResendInviteActionTest extends AbstractTeamInviteControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_resendinvite';
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

    public function testResendInviteActionPostRequestPrivateUser()
    {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

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
                'user' => self::INVITEE_EMAIL,
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
     * @dataProvider resendInviteGetInviteFailureDataProvider
     */
    public function testResendInviteActionGetInviteFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    public function resendInviteGetInviteFailureDataProvider(): array
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
                    TeamInviteController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider resendInviteActionSendInviteFailureDataProvider
     */
    public function testResendInviteActionSendInviteFailure(
        PostmarkException $postmarkException,
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
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendTeamInviteForExistingUser')
            ->withArgs(function (Invite $invite) use ($inviteData) {
                $this->assertEquals($inviteData['user'], $invite->getUser());
                $this->assertEquals($inviteData['team'], $invite->getTeam());
                $this->assertEquals($inviteData['token'], $invite->getToken());

                return true;
            })
            ->andThrow($postmarkException);

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]), $mailer);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    public function resendInviteActionSendInviteFailureDataProvider(): array
    {
        return [
            'Postmark not allowed to send' => [
                'postmarkException' => PostmarkExceptionFactory::create(405),
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_ERROR,
                        TeamInviteController::FLASH_BAG_KEY_ERROR =>
                            TeamInviteController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider resendInviteActionSuccessDataProvider
     */
    public function testResendInviteActionSuccess(
        array $httpFixtures,
        string $expectedMailerMethodName,
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

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive($expectedMailerMethodName)
            ->withArgs(function (Invite $invite) use ($inviteData) {
                $this->assertEquals($inviteData['user'], $invite->getUser());
                $this->assertEquals($inviteData['team'], $invite->getTeam());
                $this->assertEquals($inviteData['token'], $invite->getToken());

                return true;
            });

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]), $mailer);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    public function resendInviteActionSuccessDataProvider(): array
    {
        return [
            'user is enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedMailerMethodName' => 'sendTeamInviteForExistingUser',
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_SUCCESS,
                        TeamInviteController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
            'user not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedMailerMethodName' => 'sendTeamInviteForNewUser',
                'expectedFlashBagValues' => [
                    TeamInviteController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamInviteController::FLASH_BAG_KEY_STATUS => TeamInviteController::FLASH_BAG_STATUS_SUCCESS,
                        TeamInviteController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamInviteController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    private function callResendInviteAction(Request $request, Mailer $mailer = null): RedirectResponse
    {
        $mailer = empty($mailer)
            ? \Mockery::mock(Mailer::class)
            : $mailer;

        return $this->teamInviteController->resendInviteAction($mailer, $request);
    }
}
