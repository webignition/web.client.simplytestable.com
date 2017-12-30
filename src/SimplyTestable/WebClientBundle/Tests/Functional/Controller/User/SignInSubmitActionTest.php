<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SignInSubmitActionTest extends AbstractUserControllerTest
{
    public function testSignInSubmitActionPostRequest()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
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
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignInSubmitActionBadRequest(
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');

        $this->container->set('request', $request);
        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction();

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

        $this->container->set('request', $request);
        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction();

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
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER,
                    ]
                ],
            ],
            'user is enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
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

        $this->container->set('request', $request);

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
        $response = $this->userController->signInSubmitAction();

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
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
                ],
            ],
            'authentication success and user is not enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInSubmitActionSuccessDataProvider
     *
     * @param Request $request
     * @param bool $expectedResponseHasUserCookie
     */
    public function testSignInSubmitActionSuccess(
        Request $request,
        $expectedResponseHasUserCookie
    ) {
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpFixtures = [
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
        ];

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $this->container->set('request', $request);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction();

        $this->assertEquals('http://localhost/', $response->getTargetUrl());

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
            ],
            'stay-signed-in true' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'stay-signed-in' => true,
                ]),
                'expectedResponseHasUserCookie' => true,
            ],
        ];
    }
}
