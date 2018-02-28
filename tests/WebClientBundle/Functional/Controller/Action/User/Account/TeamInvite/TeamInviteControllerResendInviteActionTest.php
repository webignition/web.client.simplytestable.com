<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\TeamInvite;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamInviteController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use webignition\SimplyTestableUserModel\User;

class TeamInviteControllerResendInviteActionTest extends AbstractTeamInviteControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_resendinvite';
    const USER_USERNAME = 'user@example.com';
    const INVITEE_EMAIL = 'invitee@example.com';
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
        $mailService = $this->container->get(MailService::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($inviteData),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
            self::INVITEE_EMAIL
        ));

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME),
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
     *
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testResendInviteActionGetInviteFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $session = $this->container->get('session');

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function resendInviteGetInviteFailureDataProvider()
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
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws MailConfigurationException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testResendInviteActionSendInviteFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($inviteData),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function resendInviteActionSendInviteFailureDataProvider()
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
     *
     * @param array $httpFixtures
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testResendInviteActionSuccess(
        array $httpFixtures,
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures(array_merge(
            [
                HttpResponseFactory::createJsonResponse($inviteData),
            ],
            $httpFixtures
        ));

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function resendInviteActionSuccessDataProvider()
    {
        return [
            'user is enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
                    self::INVITEE_EMAIL
                ),
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
                'postmarkMessage' => MockPostmarkMessageFactory::createMockTeamInviteSuccessPostmarkMessage(
                    self::INVITEE_EMAIL
                ),
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

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    private function callResendInviteAction(Request $request)
    {
        return $this->teamInviteController->resendInviteAction(
            $this->container->get(MailService::class),
            $this->container->get('twig'),
            $request
        );
    }
}
