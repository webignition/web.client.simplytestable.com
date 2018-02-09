<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class TeamControllerResendInviteActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_resendinvite';
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

    public function testResendInviteActionPostRequestPrivateUser()
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
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($inviteData),
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
     * @throws CoreApplicationAdminRequestException
     * @throws Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException
     */
    public function testResendInviteActionGetInviteFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $session = $this->container->get('session');

        $coreApplicationHttpClient->setUser($this->user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->teamController->resendInviteAction(new Request([], [
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
                    Response::fromMessage(sprintf(
                        "HTTP/1.1 400 Bad Request\nX-TeamInviteGet-Error-Code:%s\nX-TeamInviteGet-Error-Message:%s",
                        2,
                        'Invitee is a team leader'
                    )),
                ],
                'expectedFlashBagValues' => [
                    TeamController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_TEAM_INVITE_GET_ERROR_INVITEE_IS_A_TEAM_LEADER,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
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
     * @throws CoreApplicationAdminRequestException
     * @throws Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException
     */
    public function testResendInviteActionSendInviteFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $coreApplicationHttpClient->setUser($this->user);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($inviteData),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->teamController->resendInviteAction(new Request([], [
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
                    TeamController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_ERROR,
                        TeamController::FLASH_BAG_KEY_ERROR =>
                            TeamController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider resendInviteActionSuccessDataProvider
     *
     * @param Response[] $httpFixtures
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationAdminRequestException
     * @throws Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException
     * @throws \SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException
     */
    public function testResendInviteActionSuccess(
        array $httpFixtures,
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $coreApplicationHttpClient->setUser($this->user);

        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITEE_EMAIL,
            'token' => 'invite-token',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($inviteData),
        ]);

        $this->setHttpFixtures($httpFixtures);

        $mailService->setPostmarkMessage($postmarkMessage);

        /* @var RedirectResponse $response */
        $response = $this->teamController->resendInviteAction(new Request([], [
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
                    TeamController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_SUCCESS,
                        TeamController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
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
                    TeamController::FLASH_BAG_TEAM_RESEND_INVITE_KEY => [
                        TeamController::FLASH_BAG_KEY_STATUS => TeamController::FLASH_BAG_STATUS_SUCCESS,
                        TeamController::FLASH_BAG_KEY_TEAM => self::TEAM_NAME,
                        TeamController::FLASH_BAG_KEY_INVITEE => self::INVITEE_EMAIL,
                    ],
                ],
            ],
        ];
    }
}
