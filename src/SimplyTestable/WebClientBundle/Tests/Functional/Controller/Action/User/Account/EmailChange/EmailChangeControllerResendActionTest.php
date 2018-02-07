<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class EmailChangeControllerResendActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_resend';
    const NEW_EMAIL = 'new-email@example.com';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/';

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

        $this->user = new User('user@example.com');
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

    public function testResendActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
            self::NEW_EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'email-change-request-token',
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        $user = new User('user@example.com', 'password');

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider resendActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testResendActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $userService->setUser($this->user);
        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'email-change-request-token',
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->resendAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function resendActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 405,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_RESEND_ERROR_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ],
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 406,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_RESEND_ERROR_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ],
                 ],
            ],
            'postmark invaild email address' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 300,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_RESEND_ERROR_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID,
                    ],
                 ],
            ],
            'postmark unknown error' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 206,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_RESEND_ERROR_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                    ],
                ],
            ],
        ];
    }

    public function testRequestActionSuccess()
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $userService->setUser($this->user);
        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
            self::NEW_EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));
        $coreApplicationHttpClient->setUser($this->user);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => 'email-change-request-token',
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->resendAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_RESEND_SUCCESS_KEY => [
                EmailChangeController::FLASH_BAG_RESEND_MESSAGE_SUCCESS
            ],
        ], $session->getFlashBag()->peekAll());
    }
}
