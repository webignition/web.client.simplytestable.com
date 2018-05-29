<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\User;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Services\PostmarkSender;
use SimplyTestable\WebClientBundle\Services\Request\Factory\User\SignUpRequestFactory;
use SimplyTestable\WebClientBundle\Services\Request\Validator\User\UserAccountRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Tests\WebClientBundle\Helper\MockeryArgumentValidator;

class SignUpSubmitActionTest extends AbstractUserControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';

    public function testSignUpSubmitActionPostRequest()
    {
        $mailService = $this->container->get(MailService::class);

        $postmarkMessage = MockPostmarkMessageFactory::createMockActivateAccountPostmarkMessage(
            self::EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        );

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('sign_up_submit');

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'plan' => 'basic',
                'email' => self::EMAIL,
                'password' => 'password',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            '/signup/confirm/user@example.com/',
            $response->getTargetUrl()
        );
    }

    public function testSignUpSubmitActionBadRequest()
    {
        $request = new Request([], ['plan' => 'personal']);
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

        $session = $this->container->get('session');

        $response = $this->callSignUpSubmitAction($request, $userAccountRequestValidator);

        $this->assertEquals('/signup/?plan=personal', $response->getTargetUrl());
        $this->assertEquals(
            [
                UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['empty'],
            ],
            $session->getFlashBag()->peekAll()
        );
    }

    /**
     * @dataProvider signUpSubmitActionUserCreationFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws MailConfigurationException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testSignUpSubmitActionUserCreationFailure(
        array $httpFixtures,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => self::EMAIL,
            'password' => 'password',
        ]);

        $response = $this->callSignUpSubmitAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signUpSubmitActionUserCreationFailureDataProvider()
    {
        return [
            'user already exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createRedirectResponse(),
                ],
                'expectedRedirectLocation' => '/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_SUCCESS_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS,
                    ]
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
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY,
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
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpSubmitActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignUpSubmitActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $postmarkSender = $this->container->get(PostmarkSender::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => self::EMAIL,
            'password' => 'password',
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $response = $this->callSignUpSubmitAction($request);

        $this->assertEquals(
            '/signup/?email=user%40example.com&plan=basic',
            $response->getTargetUrl()
        );

        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function signUpSubmitActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockActivateAccountPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 405,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockActivateAccountPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 406,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'invalid email address' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockActivateAccountPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 300,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpSubmitActionSuccessDataProvider
     *
     * @param Request $request
     * @param array $couponData
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignUpSubmitActionSuccess(Request $request, array $couponData)
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $postmarkSender = $this->container->get(PostmarkSender::class);
        $couponService = $this->container->get(CouponService::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $postmarkMessage = MockPostmarkMessageFactory::createMockPostmarkMessage(
            self::EMAIL,
            MockPostmarkMessageFactory::SUBJECT_ACTIVATE_YOUR_ACCOUNT,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ],
            [
                'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                    sprintf(
                        'http://localhost/signup/confirm/%s/?token=%s',
                        self::EMAIL,
                        self::CONFIRMATION_TOKEN
                    )
                ])),
            ]
        );

        $mailService->setPostmarkMessage($postmarkMessage);

        $couponService->setCouponData($couponData);

        $response = $this->callSignUpSubmitAction($request);

        $this->assertEquals(
            sprintf('/signup/confirm/%s/', self::EMAIL),
            $response->getTargetUrl()
        );

        $this->assertEquals(
            [
                UserController::FLASH_BAG_SIGN_UP_SUCCESS_KEY => [
                    UserController::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED,
                ]
            ],
            $session->getFlashBag()->peekAll()
        );

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function signUpSubmitActionSuccessDataProvider()
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

    /**
     * @param Request $request
     * @param UserAccountRequestValidator|null $userAccounRequestValidator
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    private function callSignUpSubmitAction(Request $request, $userAccounRequestValidator = null)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $signInRequestFactory = new SignUpRequestFactory($requestStack);

        if (empty($userAccounRequestValidator)) {
            $userAccounRequestValidator = new UserAccountRequestValidator(new EmailValidator());
        }

        return $this->userController->signUpSubmitAction(
            $this->container->get(MailService::class),
            $this->container->get(CouponService::class),
            $this->container->get('twig'),
            $signInRequestFactory,
            $userAccounRequestValidator,
            $request
        );

//        return $this->userController->signUpSubmitAction(
//            $this->container->get(MailService::class),
//            $this->container->get(CouponService::class),
//            $this->container->get('twig'),
//            $request
//        );
    }
}
