<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\User\User;

use App\Services\HoneypotFieldName;
use App\Services\Mailer;
use App\Tests\Factory\PostmarkExceptionFactory;
use Egulias\EmailValidator\EmailValidator;
use Postmark\Models\PostmarkException;
use App\Controller\Action\User\UserController;
use App\Request\User\SignUpRequest;
use App\Services\CouponService;
use App\Services\Request\Factory\User\SignUpRequestFactory;
use App\Services\Request\Validator\User\UserAccountRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class SignUpSubmitActionTest extends AbstractUserControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';
    const HONEYPOT_FIELD_NAME = 'honeypot-field-name';

    public function testSignUpSubmitActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $honeypotFieldNameService = self::$container->get(HoneypotFieldName::class);

        $this->client->request(
            'POST',
            $this->router->generate('action_user_sign_up_request'),
            [
                'plan' => 'basic',
                'email' => self::EMAIL,
                'password' => 'password',
                $honeypotFieldNameService->get() => '',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            '/signup/confirm/user@example.com/',
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider signUpSubmitActionInvalidHoneypotDataProvider
     */
    public function testSignUpSubmitActionInvalidHoneypot(Request $request)
    {
        $honeypotFieldNameService = \Mockery::mock(HoneypotFieldName::class);
        $honeypotFieldNameService
            ->shouldReceive('get')
            ->andReturn(self::HONEYPOT_FIELD_NAME);

        $response = $this->callSignUpSubmitAction($request, [
            HoneypotFieldName::class => $honeypotFieldNameService,
        ]);

        $this->assertEquals('/signup/', $response->getTargetUrl());
    }

    public function signUpSubmitActionInvalidHoneypotDataProvider(): array
    {
        return [
            'null' => [
                'request' => new Request(),
            ],
            'non-empty' => [
                'request' => new Request([], [
                    self::HONEYPOT_FIELD_NAME => 'non-empty',
                ]),
            ],
        ];
    }

    public function testSignUpSubmitActionBadRequest()
    {
        $request = new Request([], [
            'plan' => 'personal',
            self::HONEYPOT_FIELD_NAME => '',
        ]);

        $honeypotFieldNameService = \Mockery::mock(HoneypotFieldName::class);
        $honeypotFieldNameService
            ->shouldReceive('get')
            ->andReturn(self::HONEYPOT_FIELD_NAME);

        $userAccountRequestValidator = \Mockery::mock(UserAccountRequestValidator::class);
        $userAccountRequestValidator
            ->shouldReceive('validate')
            ->withArgs(function () {
                return true;
            });

        $userAccountRequestValidator
            ->shouldReceive('getIsValid')
            ->andReturn(false);

        $userAccountRequestValidator
            ->shouldReceive('getInvalidFieldName')
            ->andReturn('email');

        $userAccountRequestValidator
            ->shouldReceive('getInvalidFieldState')
            ->andReturn('empty');

        $flashBag = self::$container->get(FlashBagInterface::class);

        $response = $this->callSignUpSubmitAction($request, [
            UserAccountRequestValidator::class => $userAccountRequestValidator,
            HoneypotFieldName::class => $honeypotFieldNameService,
        ]);

        $this->assertEquals('/signup/?plan=personal', $response->getTargetUrl());
        $this->assertEquals(
            [
                UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_UP_ERROR_STATE_KEY => ['empty'],
            ],
            $flashBag->peekAll()
        );
    }

    /**
     * @dataProvider signUpSubmitActionUserCreationFailureDataProvider
     */
    public function testSignUpSubmitActionUserCreationFailure(
        array $httpFixtures,
        string $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $honeypotFieldNameService = self::$container->get(HoneypotFieldName::class);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => self::EMAIL,
            'password' => 'password',
            $honeypotFieldNameService->get() => '',
        ]);

        $response = $this->callSignUpSubmitAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    public function signUpSubmitActionUserCreationFailureDataProvider(): array
    {
        return [
            'user already exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createRedirectResponse(),
                ],
                'expectedRedirectLocation' => '/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [
                        SignUpRequest::PARAMETER_EMAIL,
                    ],
                    UserController::FLASH_SIGN_UP_SUCCESS_KEY => [
                        UserController::FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS,
                    ],
                ],
            ],
            'failed due to read-only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'expectedRedirectLocation' => '/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [],
                    UserController::FLASH_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY,
                    ]
                ],
            ],
            'failed, unknown' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedRedirectLocation' => '/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [],
                    UserController::FLASH_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpSubmitActionSendConfirmationTokenFailureDataProvider
     */
    public function testSignUpSubmitActionSendConfirmationTokenFailure(
        PostmarkException $postmarkException,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $honeypotFieldNameService = self::$container->get(HoneypotFieldName::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendSignUpConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ])
            ->andThrow($postmarkException);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => self::EMAIL,
            'password' => 'password',
            $honeypotFieldNameService->get() => '',
        ]);

        $response = $this->callSignUpSubmitAction($request, [
            Mailer::class => $mailer,
        ]);

        $this->assertEquals(
            '/signup/?email=user%40example.com&plan=basic',
            $response->getTargetUrl()
        );

        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    public function signUpSubmitActionSendConfirmationTokenFailureDataProvider(): array
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkException' => PostmarkExceptionFactory::create(405),
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [
                        SignUpRequest::PARAMETER_EMAIL,
                    ],
                    UserController::FLASH_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkException' => PostmarkExceptionFactory::create(406),
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [
                        SignUpRequest::PARAMETER_EMAIL,
                    ],
                    UserController::FLASH_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'invalid email address' => [
                'postmarkException' => PostmarkExceptionFactory::create(300),
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [
                        SignUpRequest::PARAMETER_EMAIL,
                    ],
                    UserController::FLASH_SIGN_UP_ERROR_STATE_KEY => [
                        UserAccountRequestValidator::STATE_INVALID,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpSubmitActionSuccessDataProvider
     */
    public function testSignUpSubmitActionSuccess(Request $request, array $couponData)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $couponService = self::$container->get(CouponService::class);
        $honeypotFieldNameService = self::$container->get(HoneypotFieldName::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $couponService->setCouponData($couponData);

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendSignUpConfirmationToken')
            ->withArgs([
                self::EMAIL,
                self::CONFIRMATION_TOKEN,
            ]);

        $request->request->set($honeypotFieldNameService->get(), '');

        $response = $this->callSignUpSubmitAction($request, [
            Mailer::class => $mailer,
        ]);

        $this->assertEquals(
            sprintf('/signup/confirm/%s/', self::EMAIL),
            $response->getTargetUrl()
        );

        $this->assertEquals(
            [
                UserController::FLASH_SIGN_UP_SUCCESS_KEY => [
                    UserController::FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED,
                ]
            ],
            $flashBag->peekAll()
        );
    }

    public function signUpSubmitActionSuccessDataProvider(): array
    {
        return [
            'without coupon' => [
                'request' => new Request([], [
                    'plan' => 'basic',
                    'email' => self::EMAIL,
                    'password' => 'password',
                ]),
                'couponData' => [],
            ],
            'with active coupon' => [
                'request' => new Request(
                    [],
                    [
                        'plan' => 'basic',
                        'email' => self::EMAIL,
                        'password' => 'password',
                    ],
                    [],
                    [
                        CouponService::COUPON_COOKIE_NAME => 'foo',
                    ]
                ),
                'couponData' => [
                    'foo' => [
                        'active' => true,
                        'percent_off' => 20,
                        'intro' => '',
                    ],
                ],
            ],
            'with inactive coupon' => [
                'request' => new Request(
                    [],
                    [
                    'plan' => 'basic',
                    'email' => self::EMAIL,
                    'password' => 'password',
                    ],
                    [],
                    [
                        CouponService::COUPON_COOKIE_NAME => 'bar',
                    ]
                ),
                'couponData' => [
                    'foo' => [
                        'active' => true,
                        'percent_off' => 20,
                        'intro' => '',
                    ],
                    'bar' => [
                        'active' => false,
                        'percent_off' => 20,
                        'intro' => '',
                    ],
                ],
            ],
        ];
    }

    private function callSignUpSubmitAction(Request $request, array $services = []): RedirectResponse
    {
        if (!isset($services[Mailer::class])) {
            $services[Mailer::class] = self::$container->get(Mailer::class);
        }

        if (!isset($services[CouponService::class])) {
            $services[CouponService::class] = self::$container->get(CouponService::class);
        }

        if (!isset($services[UserAccountRequestValidator::class])) {
            $services[UserAccountRequestValidator::class] = new UserAccountRequestValidator(new EmailValidator());
        }

        if (!isset($services[HoneypotFieldName::class])) {
            $services[HoneypotFieldName::class] = self::$container->get(HoneypotFieldName::class);
        }

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $signInRequestFactory = new SignUpRequestFactory($requestStack);

        return $this->userController->signUpSubmitAction(
            $services[Mailer::class],
            $services[CouponService::class],
            $signInRequestFactory,
            $services[UserAccountRequestValidator::class],
            $request,
            $services[HoneypotFieldName::class]
        );
    }
}
