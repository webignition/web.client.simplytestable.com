<?php

namespace App\Tests\Functional\Controller\Action\SignUp\User;

use App\Services\Mailer;
use App\Services\UserService;
use App\Tests\Factory\PostmarkExceptionFactory;
use Mockery\MockInterface;
use Postmark\Models\PostmarkException;
use App\Controller\Action\SignUp\User\ConfirmController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class ConfirmControllerTest extends AbstractControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';
    const EXPECTED_REDIRECT_URL = '/signup/confirm/'. self::EMAIL .'/';

    /**
     * @var ConfirmController
     */
    private $confirmController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->confirmController = self::$container->get(ConfirmController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testResendActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('action_sign_up_user_confirm_resend', [
                'email' => self::EMAIL,
            ])
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    public function testResendActionUserDoesNotExist()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID,
                ],
            ],
            $flashBag->peekAll()
        );
    }

    public function testResendActionInvalidAdminCredentials()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendInvalidAdminCredentialsNotification')
            ->withArgs([[
                'call' => 'UserService::exists()',
                'args' => [
                    'email' => self::EMAIL,
                ]
            ]]);

        $confirmController = new ConfirmController(
            self::$container->get(RouterInterface::class),
            self::$container->get(UserService::class),
            self::$container->get(FlashBagInterface::class),
            $mailer
        );

        /* @var RedirectResponse $response */
        $response = $confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID,
                ],
            ],
            $flashBag->peekAll()
        );
    }

    /**
     * @dataProvider resendActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkException $postmarkException
     * @param array $expectedFlashBagValues
     *
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testResendActionSendConfirmationTokenFailure(
        PostmarkException $postmarkException,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendSignUpConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ])
            ->andThrow($postmarkException);

        $confirmController = new ConfirmController(
            self::$container->get(RouterInterface::class),
            self::$container->get(UserService::class),
            self::$container->get(FlashBagInterface::class),
            $mailer
        );

        /* @var RedirectResponse $response */
        $response = $confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    /**
     * @return array
     */
    public function resendActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkException' => PostmarkExceptionFactory::create(405),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkException' => PostmarkExceptionFactory::create(406),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'postmark unknown error' => [
                'postmarkException' => PostmarkExceptionFactory::create(300),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                    ]
                ],
            ],
        ];
    }

    public function testResendActionSuccess()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendSignUpConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ]);

        $confirmController = new ConfirmController(
            self::$container->get(RouterInterface::class),
            self::$container->get(UserService::class),
            self::$container->get(FlashBagInterface::class),
            $mailer
        );

        /* @var RedirectResponse $response */
        $response = $confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE,
                ]
            ],
            $flashBag->peekAll()
        );
    }
}
