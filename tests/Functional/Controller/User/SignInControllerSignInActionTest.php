<?php

namespace App\Tests\Functional\Controller\User;

use App\Controller\User\SignInController;
use App\Services\RedirectResponseFactory;
use App\Services\UserService;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Egulias\EmailValidator\EmailValidator;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use App\Controller\Action\User\UserController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Services\Configuration\MailConfiguration;
use App\Services\Request\Factory\User\SignInRequestFactory;
use App\Services\Request\Validator\User\SignInRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use App\Tests\Services\PostmarkMessageVerifier;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class SignInSubmitActionTest extends AbstractControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';

    public function testSignInActionPostRequest()
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('sign_in_action'),
            [
                'email' => self::EMAIL,
                'password' => 'foo',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function testSignInActionBadRequest()
    {
        $request = new Request();
        $signInRequestValidator = \Mockery::mock(SignInRequestValidator::class);

        $signInRequestValidator
            ->shouldReceive('validate')
            ->withArgs(function () {
                return true;
            });

        $signInRequestValidator
            ->shouldReceive('getIsValid')
            ->andReturn(false);

        $signInRequestValidator
            ->shouldReceive('getInvalidFieldName')
            ->andReturn('email');

        $signInRequestValidator
            ->shouldReceive('getInvalidFieldState')
            ->andReturn('empty');

        $flashBag = self::$container->get(FlashBagInterface::class);

        $response = $this->callSignInAction($request, $signInRequestValidator);

        $this->assertEquals('/signin/?stay-signed-in=0', $response->getTargetUrl());
        $this->assertEquals(
            [
                UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['empty'],
            ],
            $flashBag->peekAll()
        );
    }

    /**
     * @dataProvider signInActionAuthenticationFailureDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignInActionAuthenticationFailure(
        array $httpFixtures,
        array $expectedFlashBagValues
    ) {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request([], [
            'email' => self::EMAIL,
            'password' => 'foo',
        ]);

        $response = $this->callSignInAction($request);

        $this->assertEquals(
            '/signin/?email=user%40example.com&stay-signed-in=0',
            $response->getTargetUrl()
        );
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    /**
     * @return array
     */
    public function signInActionAuthenticationFailureDataProvider()
    {
        return [
            'user does not exist' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                    UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['invalid-user'],
                ],
            ],
            'user is enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedFlashBagValues' => [
                    UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                    UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['authentication-failure'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInActionResendConfirmationTokenDataProvider
     *
     * @param array $httpFixtures
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function testSignInActionResendConfirmationToken(array $httpFixtures)
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $flashBag = self::$container->get(FlashBagInterface::class);
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

        $httpMockHandler->appendFixtures(array_merge($httpFixtures, [
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]));

        $request = new Request([], [
            'email' => self::EMAIL,
            'password' => 'foo',
        ]);

        $response = $this->callSignInAction($request);

        $this->assertEquals(
            sprintf('/signin/?email=%s&stay-signed-in=0', urlencode(self::EMAIL)),
            $response->getTargetUrl()
        );

        $this->assertEquals(
            [
                UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['user-not-enabled'],
            ],
            $flashBag->peekAll()
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
                    sprintf(
                        'http://localhost/signup/confirm/%s/?token=%s',
                        self::EMAIL,
                        self::CONFIRMATION_TOKEN
                    ),
                ],
            ],
            $httpHistoryContainer->getLastRequest()
        );

        $this->assertTrue($verificationResult, $verificationResult);
    }

    /**
     * @return array
     */
    public function signInActionResendConfirmationTokenDataProvider()
    {
        return [
            'authentication failure and user is not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                ],
            ],
            'authentication success and user is not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInActionSuccessDataProvider
     *
     * @param Request $request
     * @param $expectedResponseHasUserCookie
     * @param string $expectedRedirectUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignInActionSuccess(
        Request $request,
        $expectedResponseHasUserCookie,
        $expectedRedirectUrl
    ) {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);

        $httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $response = $this->callSignInAction($request);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        $responseCookies = $response->headers->getCookies();
        if ($expectedResponseHasUserCookie) {
            /* @var Cookie $rememberUserCookie */
            $rememberUserCookie = $responseCookies[0];

            $this->assertEquals('simplytestable-user', $rememberUserCookie->getName());
        } else {
            $this->assertEquals([], $responseCookies);
        }
    }

    /**
     * @return array
     */
    public function signInActionSuccessDataProvider()
    {
        return [
            'stay-signed-in false' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/',
            ],
            'stay-signed-in true' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'stay-signed-in' => true,
                ]),
                'expectedResponseHasUserCookie' => true,
                'expectedRedirectUrl' => '/',
            ],
            'redirect without route, without parameters' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/',
            ],
            'redirect with invalid route, without parameters' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'foo',
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/',
            ],
            'redirect with valid route, without parameters' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'view_user_account_card',
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/account/card/',
            ],
            'redirect with valid route, invalid parameters' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'view_test_progress',
                        'parameters' => [
                            'website' => 'http://example.com',
                        ],
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/',
            ],
            'redirect with valid route, with parameters' => [
                'request' => new Request([], [
                    'email' => self::EMAIL,
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'view_test_progress',
                        'parameters' => [
                            'website' => 'http://example.com',
                            'test_id' => 1,
                        ],
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => '/http://example.com/1/progress/',
            ],
        ];
    }

    /**
     * @param Request $request
     * @param SignInRequestValidator|null $signInRequestValidator
     *
     * @return RedirectResponse
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    private function callSignInAction(Request $request, $signInRequestValidator = null)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $signInRequestFactory = new SignInRequestFactory($requestStack);

        if (empty($signInRequestValidator)) {
            $signInRequestValidator = new SignInRequestValidator(new EmailValidator());
        }

        /* @var SignInController $signInController */
        $signInController = self::$container->get(SignInController::class);

        return $signInController->signInAction(
            self::$container->get(MailConfiguration::class),
            self::$container->get(PostmarkClient::class),
            $signInRequestFactory,
            $signInRequestValidator,
            self::$container->get(FlashBagInterface::class),
            self::$container->get(RedirectResponseFactory::class),
            self::$container->get(UserService::class)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
