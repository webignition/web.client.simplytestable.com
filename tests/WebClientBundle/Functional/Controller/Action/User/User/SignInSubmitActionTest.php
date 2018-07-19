<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\User;

use Egulias\EmailValidator\EmailValidator;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\Configuration\MailConfiguration;
use SimplyTestable\WebClientBundle\Services\Request\Factory\User\SignInRequestFactory;
use SimplyTestable\WebClientBundle\Services\Request\Validator\User\SignInRequestValidator;
use Symfony\Component\HttpFoundation\RequestStack;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Tests\WebClientBundle\Factory\PostmarkHttpResponseFactory;
use Tests\WebClientBundle\Services\PostmarkMessageVerifier;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class SignInSubmitActionTest extends AbstractUserControllerTest
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';

    public function testSignInSubmitActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('sign_in_submit'),
            [
                'email' => self::EMAIL,
                'password' => 'foo',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals('/', $response->getTargetUrl());
    }

    public function testSignInSubmitActionBadRequest()
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

        $session = self::$container->get('session');

        $response = $this->callSignInSubmitAction($request, $signInRequestValidator);

        $this->assertEquals('/signin/?stay-signed-in=0', $response->getTargetUrl());
        $this->assertEquals(
            [
                UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['empty'],
            ],
            $session->getFlashBag()->peekAll()
        );
    }

    /**
     * @dataProvider signInSubmitActionAuthenticationFailureDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function testSignInSubmitActionAuthenticationFailure(
        array $httpFixtures,
        array $expectedFlashBagValues
    ) {
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request([], [
            'email' => self::EMAIL,
            'password' => 'foo',
        ]);

        $response = $this->callSignInSubmitAction($request);

        $this->assertEquals(
            '/signin/?email=user%40example.com&stay-signed-in=0',
            $response->getTargetUrl()
        );
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signInSubmitActionAuthenticationFailureDataProvider()
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
     * @dataProvider signInSubmitActionResendConfirmationTokenDataProvider
     *
     * @param array $httpFixtures
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function testSignInSubmitActionResendConfirmationToken(array $httpFixtures)
    {
        $session = self::$container->get('session');
        $httpHistoryContainer = self::$container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = self::$container->get(PostmarkMessageVerifier::class);

        $this->httpMockHandler->appendFixtures(array_merge($httpFixtures, [
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]));

        $request = new Request([], [
            'email' => self::EMAIL,
            'password' => 'foo',
        ]);

        $response = $this->callSignInSubmitAction($request);

        $this->assertEquals(
            sprintf('/signin/?email=%s&stay-signed-in=0', urlencode(self::EMAIL)),
            $response->getTargetUrl()
        );

        $this->assertEquals(
            [
                UserController::FLASH_SIGN_IN_ERROR_FIELD_KEY => ['email'],
                UserController::FLASH_SIGN_IN_ERROR_STATE_KEY => ['user-not-enabled'],
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
    public function signInSubmitActionResendConfirmationTokenDataProvider()
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
     * @dataProvider signInSubmitActionSuccessDataProvider
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
    public function testSignInSubmitActionSuccess(
        Request $request,
        $expectedResponseHasUserCookie,
        $expectedRedirectUrl
    ) {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $response = $this->callSignInSubmitAction($request);

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
    public function signInSubmitActionSuccessDataProvider()
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
                        'route' => 'view_user_account_card_index',
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
                        'route' => 'view_test_progress_index_index',
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
                        'route' => 'view_test_progress_index_index',
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
    private function callSignInSubmitAction(Request $request, $signInRequestValidator = null)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        $signInRequestFactory = new SignInRequestFactory($requestStack);

        if (empty($signInRequestValidator)) {
            $signInRequestValidator = new SignInRequestValidator(new EmailValidator());
        }

        return $this->userController->signInSubmitAction(
            self::$container->get(MailConfiguration::class),
            self::$container->get(PostmarkClient::class),
            self::$container->get('twig'),
            $signInRequestFactory,
            $signInRequestValidator
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
