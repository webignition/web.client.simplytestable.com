<?php

namespace App\Tests\Functional\Controller\Action\User;

use App\Exception\InvalidCredentialsException;
use App\Services\Mailer;
use App\Services\UserManager;
use App\Services\UserService;
use App\Tests\Factory\PostmarkExceptionFactory;
use Mockery\MockInterface;
use Postmark\Models\PostmarkException;
use App\Controller\Action\User\ResetPasswordController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class ResetPasswordControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'action_user_reset_password_request';
    const EMAIL = 'user@example.com';
    const PASSWORD = 'password-value';
    const CONFIRMATION_TOKEN = 'confirmation-token';

    /**
     * @var ResetPasswordController
     */
    private $resetPasswordController;

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

        $this->resetPasswordController = self::$container->get(ResetPasswordController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testRequestActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME),
            [
                'email' => self::EMAIL
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            '/reset-password/?email=user%40example.com',
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider requestActionBadRequestDataProvider
     *
     * @param Request $request
     * @param array $expectedFlashBagValues
     * @param string $expectedRedirectUrl
     *
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testRequestActionBadRequest(Request $request, array $expectedFlashBagValues, $expectedRedirectUrl)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        /* @var RedirectResponse $response */
        $response = $this->resetPasswordController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function requestActionBadRequestDataProvider()
    {
        return [
            'empty email' => [
                'request' => new Request(),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                    ],
                ],
                'expectedRedirectUrl' => '/reset-password/',
            ],
            'invalid email' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID,
                    ],
                ],
                'expectedRedirectUrl' => '/reset-password/?email=foo',
            ],
        ];
    }

    /**
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testRequestActionUserDoesNotExist()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->resetPasswordController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID,
                ],
            ],
            $flashBag->peekAll()
        );
    }

    /**
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testRequestActionInvalidAdminCredentials()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
            PostmarkHttpResponseFactory::createSuccessResponse(),
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

        $resetPasswordController = $this->createResetPasswordController($mailer);

        /* @var RedirectResponse $response */
        $response = $resetPasswordController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS,
                ],
            ],
            $flashBag->peekAll()
        );
    }


    /**
     * @dataProvider requestActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkException $postmarkException
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testRequestActionSendConfirmationTokenFailure(
        PostmarkException $postmarkException,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendPasswordResetConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ])
            ->andThrow($postmarkException);

        $resetPasswordController = $this->createResetPasswordController($mailer);

        /* @var RedirectResponse $response */
        $response = $resetPasswordController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    /**
     * @return array
     */
    public function requestActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkException' => PostmarkExceptionFactory::create(405),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkException' => PostmarkExceptionFactory::create(406),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                    ]
                ],
            ],
            'postmark invalid email' => [
                'postmarkException' => PostmarkExceptionFactory::create(300),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL,
                    ]
                ],
            ],
            'postmark unknown error' => [
                'postmarkException' => PostmarkExceptionFactory::create(303),
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                    ]
                ],
            ],
        ];
    }

    public function testRequestActionSuccess()
    {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        /* @var MockInterface|Mailer $mailer */
        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendPasswordResetConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ]);

        $resetPasswordController = $this->createResetPasswordController($mailer);

        /* @var RedirectResponse $response */
        $response = $resetPasswordController->requestAction(new Request([], [
            'email' => self::EMAIL,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                ResetPasswordController::FLASH_BAG_REQUEST_SUCCESS_KEY => [
                    ResetPasswordController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                ]
            ],
            $flashBag->peekAll()
        );
    }

    public function testChooseActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('action_user_reset_password_choose'),
            [
                'email' => self::EMAIL,
                'token' => self::CONFIRMATION_TOKEN,
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }

    /**
     * @dataProvider chooseActionDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     * @param bool $expectedResponseHasUserCookie
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testChooseAction(
        array $httpFixtures,
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues,
        $expectedResponseHasUserCookie
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->resetPasswordController->chooseAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());

        /* @var array $responseCookies */
        $responseCookies = $response->headers->getCookies();

        if ($expectedResponseHasUserCookie) {
            $this->assertCount(1, $responseCookies);

            $responseCookie = $responseCookies[0];
            $this->assertEquals(UserManager::USER_COOKIE_KEY, $responseCookie->getName());
        } else {
            $this->assertEmpty($responseCookies);
        }
    }

    /**
     * @return array
     */
    public function chooseActionDataProvider()
    {
        return [
            'empty email' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => '',
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid email' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => 'foo',
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'empty token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => '',
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => 'foo',
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/user@example.com/foo/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_TOKEN_INVALID,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'empty password' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => '',
                ]),
                'expectedRedirectLocation' =>
                    '/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_PASSWORD_BLANK,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'failed; HTTP 503' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' =>
                    '/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        ResetPasswordController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'success, stay-signed-in=0' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'success, stay-signed-in=1' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'token' => self::CONFIRMATION_TOKEN,
                    'password' => self::PASSWORD,
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' => '/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => true,
            ],
        ];
    }

    /**
     * @param Mailer $mailer
     *
     * @return ResetPasswordController
     */
    private function createResetPasswordController(Mailer $mailer)
    {
        return new ResetPasswordController(
            self::$container->get(RouterInterface::class),
            self::$container->get(UserService::class),
            self::$container->get(\Twig_Environment::class),
            self::$container->get(FlashBagInterface::class),
            $mailer,
            self::$container->get(UserManager::class)
        );
    }
}
