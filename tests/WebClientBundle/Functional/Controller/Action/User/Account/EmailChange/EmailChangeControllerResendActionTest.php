<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\EmailChange;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Tests\WebClientBundle\Helper\MockeryArgumentValidator;
use webignition\SimplyTestableUserModel\User;

class EmailChangeControllerResendActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_resend';
    const NEW_EMAIL = 'new-email@example.com';
    const CONFIRMATION_TOKEN = 'email-change-request-token';
    const EXPECTED_REDIRECT_URL = '/account/';

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
        $mailService = $this->container->get(MailService::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
            self::NEW_EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME)
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
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    public function testResendActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);
        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        $response = $this->callResendAction();

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
        $mailService = $this->container->get(MailService::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);

        $postmarkMessage = MockPostmarkMessageFactory::createMockPostmarkMessage(
            self::NEW_EMAIL,
            MockPostmarkMessageFactory::SUBJECT_CONFIRM_EMAIL_ADDRESS_CHANGE,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ],
            [
                'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                    sprintf(
                        'http://localhost/account/?token=%s',
                        self::CONFIRMATION_TOKEN
                    )
                ])),
            ]
        );

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        $response = $this->callResendAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_RESEND_SUCCESS_KEY => [
                EmailChangeController::FLASH_BAG_RESEND_MESSAGE_SUCCESS
            ],
        ], $session->getFlashBag()->peekAll());
    }

    /**
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    private function callResendAction()
    {
        return $this->emailChangeController->resendAction(
            $this->container->get(MailService::class),
            $this->container->get('twig')
        );
    }
}
