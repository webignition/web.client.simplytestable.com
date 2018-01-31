<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class SignInSubmitActionTest extends AbstractUserControllerTest
{
    public function testSignInSubmitActionPostRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('sign_in_submit');

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'email' => 'user@example.com',
                'password' => 'foo',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals('http://localhost/', $response->getTargetUrl());
    }

    /**
     * @dataProvider signInSubmitActionBadRequestDataProvider
     *
     * @param Request $request
     * @param $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationAdminRequestException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testSignInSubmitActionBadRequest(
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signInSubmitActionBadRequestDataProvider()
    {
        return [
            'empty email' => [
                'request' => new Request(),
                'expectedRedirectLocation' => 'http://localhost/signin/?redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_BLANK,
                    ]
                ],
            ],
            'invalid email' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedRedirectLocation' => 'http://localhost/signin/?email=foo&redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_INVALID,
                    ]
                ],
            ],
            'empty password' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PASSWORD_BLANK,
                    ]
                ],
            ],
            'public user' => [
                'request' => new Request([], [
                    'email' => 'public@simplytestable.com',
                    'password' => 'foo',
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=public%40simplytestable.com&redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PUBLIC_USER,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInSubmitActionAuthenticationFailureDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationAdminRequestException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testSignInSubmitActionAuthenticationFailure(
        array $httpFixtures,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $request = new Request([], [
            'email' => 'user@example.com',
            'password' => 'foo',
        ]);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction($request);

        $this->assertEquals(
            'http://localhost/signin/?email=user%40example.com&redirect=&stay-signed-in=0',
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
                ],
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER,
                    ]
                ],
            ],
            'user is enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_AUTHENTICATION_FAILURE,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInSubmitActionResendConfirmationTokenDataProvider
     *
     * @param array $httpFixtures
     * @throws CoreApplicationAdminRequestException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testSignInSubmitActionResendConfirmationToken(array $httpFixtures)
    {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $request = new Request([], [
            'email' => 'user@example.com',
            'password' => 'foo',
        ]);

        $postmarkMessage = MockFactory::createPostmarkMessage([
            'setFrom' => true,
            'setSubject' => [
                'with' => '[Simply Testable] Activate your account',
            ],
            'setTextMessage' => true,
            'addTo' => [
                'with' => 'user@example.com',
            ],
            'send' => [
                'return' => json_encode([
                    'ErrorCode' => 0,
                    'Message' => 'OK',
                ]),
            ],
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction($request);

        $this->assertEquals(
            'http://localhost/signin/?email=user%40example.com&redirect=&stay-signed-in=0',
            $response->getTargetUrl()
        );

        $this->assertEquals(
            [
                UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED,
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
    public function signInSubmitActionResendConfirmationTokenDataProvider()
    {
        return [
            'authentication failure and user is not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createJsonResponse('confirmation-token-here'),
                ],
            ],
            'authentication success and user is not enabled' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createJsonResponse('confirmation-token-here'),
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
     * @throws CoreApplicationAdminRequestException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testSignInSubmitActionSuccess(
        Request $request,
        $expectedResponseHasUserCookie,
        $expectedRedirectUrl
    ) {
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpFixtures = [
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ];

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction($request);

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
                    'email' => 'user@example.com',
                    'password' => 'foo',
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'stay-signed-in true' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'stay-signed-in' => true,
                ]),
                'expectedResponseHasUserCookie' => true,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'redirect without route, without parameters' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'redirect with invalid route, without parameters' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'foo',
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'redirect with valid route, without parameters' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'view_user_account_card_index',
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => 'http://localhost/account/card/',
            ],
            'redirect with valid route, invalid parameters' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => base64_encode(json_encode([
                        'route' => 'view_test_progress_index_index',
                        'parameters' => [
                            'website' => 'http://example.com',
                        ],
                    ])),
                ]),
                'expectedResponseHasUserCookie' => false,
                'expectedRedirectUrl' => 'http://localhost/',
            ],
            'redirect with valid route, with parameters' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
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
                'expectedRedirectUrl' => 'http://localhost/http://example.com/1/progress/',
            ],
        ];
    }
}
