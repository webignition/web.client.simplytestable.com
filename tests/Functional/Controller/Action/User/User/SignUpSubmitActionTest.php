<?php

namespace App\Tests\Functional\Controller\Action\User\User;

use Egulias\EmailValidator\EmailValidator;
use Postmark\PostmarkClient;
use Psr\Http\Message\ResponseInterface;
use App\Controller\Action\User\UserController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Request\User\SignUpRequest;
use App\Services\Configuration\MailConfiguration;
use App\Services\CouponService;
use App\Services\Request\Factory\User\SignUpRequestFactory;
use App\Services\Request\Validator\User\UserAccountRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use App\Tests\Services\PostmarkMessageVerifier;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class SignUpSubmitActionTest extends AbstractUserControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';

    public function testSignUpSubmitActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('sign_up_submit'),
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

        $session = self::$container->get('session');

        $response = $this->callSignUpSubmitAction($request, $userAccountRequestValidator);

        $this->assertEquals('/signup/?plan=personal', $response->getTargetUrl());
        $this->assertEquals(
            [
                UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_UP_ERROR_STATE_KEY => ['empty'],
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
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures($httpFixtures);

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
     *
     * @param ResponseInterface $postmarkHttpResponse
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignUpSubmitActionSendConfirmationTokenFailure(
        ResponseInterface $postmarkHttpResponse,
        array $expectedFlashBagValues
    ) {
        $session = self::$container->get('session');
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            $postmarkHttpResponse
        ]);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => self::EMAIL,
            'password' => 'password',
        ]);

        $response = $this->callSignUpSubmitAction($request);

        $this->assertEquals(
            '/signup/?email=user%40example.com&plan=basic',
            $response->getTargetUrl()
        );

        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        $lastRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($lastRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);
    }

    /**
     * @return array
     */
    public function signUpSubmitActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(406),
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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(300),
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
        $session = self::$container->get('session');
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);
        $couponService = self::$container->get(CouponService::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $couponService->setCouponData($couponData);

        $response = $this->callSignUpSubmitAction($request);

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
            $session->getFlashBag()->peekAll()
        );

        $lastRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($lastRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);

        $verificationResult = $postmarkMessageVerifier->verify(
            [
                'From' => 'robot@simplytestable.com',
                'To' => self::EMAIL,
                'Subject' => '[Simply Testable] Activate your account',
                'TextBody' => [
                    sprintf('/signup/confirm/%s/', self::EMAIL),
                ],
            ],
            $httpHistoryContainer->getLastRequest()
        );

        $this->assertTrue($verificationResult, $verificationResult);
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
     * @param UserAccountRequestValidator|null $userAccountRequestValidator
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    private function callSignUpSubmitAction(Request $request, $userAccountRequestValidator = null)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $signInRequestFactory = new SignUpRequestFactory($requestStack);

        if (empty($userAccounRequestValidator)) {
            $userAccountRequestValidator = new UserAccountRequestValidator(new EmailValidator());
        }

        return $this->userController->signUpSubmitAction(
            self::$container->get(MailConfiguration::class),
            self::$container->get(PostmarkClient::class),
            self::$container->get(CouponService::class),
            self::$container->get('twig'),
            $signInRequestFactory,
            $userAccountRequestValidator,
            $request
        );
    }
}
