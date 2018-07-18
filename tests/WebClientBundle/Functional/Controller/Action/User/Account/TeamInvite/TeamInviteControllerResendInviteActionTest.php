<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\TeamInvite;

use Postmark\PostmarkClient;
use Psr\Http\Message\ResponseInterface;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamInviteController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\Configuration\MailConfiguration;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Tests\WebClientBundle\Factory\PostmarkHttpResponseFactory;
use Tests\WebClientBundle\Services\PostmarkMessageVerifier;
use webignition\SimplyTestableUserModel\User;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

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
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures($httpFixtures);

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
     * @param ResponseInterface $postmarkHttpResponse
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws MailConfigurationException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testResendInviteActionSendInviteFailure(
        ResponseInterface $postmarkHttpResponse,
        array $expectedFlashBagValues
    ) {
        $session = self::$container->get('session');
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

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


        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());

        $postmarkRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($postmarkRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);
    }

    /**
     * @return array
     */
    public function resendInviteActionSendInviteFailureDataProvider()
    {
        return [
            'Postmark not allowed to send' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
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
     * @param array $expectedEmailProperties
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
        array $expectedEmailProperties,
        array $expectedFlashBagValues
    ) {
        $session = self::$container->get('session');
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

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

        /* @var RedirectResponse $response */
        $response = $this->callResendInviteAction(new Request([], [
            'user' => self::INVITEE_EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());

        $postmarkRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($postmarkRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);

        $verificationResult = $postmarkMessageVerifier->verify(
            array_merge(
                [
                    'From' => 'robot@simplytestable.com',
                    'To' => self::INVITEE_EMAIL,
                    'Subject' => '[Simply Testable] You have been invited to join the Team Name team',
                    'TextBody' => [
                        'You have been invited to join the Team Name team',
                        'http://localhost/account/team',
                    ],
                ],
                $expectedEmailProperties
            ),
            $postmarkRequest
        );

        $this->assertTrue($verificationResult, $verificationResult);
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
                'expectedEmailProperties' => [
                    'TextBody' => [
                        'http://localhost/account/team/',
                    ],
                ],
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
                'expectedEmailProperties' => [
                    'TextBody' => [
                        sprintf('http://localhost/signup/invite/%s/', self::INVITE_TOKEN),
                    ],
                ],
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
            self::$container->get(MailConfiguration::class),
            self::$container->get(PostmarkClient::class),
            self::$container->get('twig'),
            $request
        );
    }
}
