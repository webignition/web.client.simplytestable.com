<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class SignInSubmitActionTest extends BaseSimplyTestableTestCase
{
    /**
     * @var UserController
     */
    private $userController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userController = new UserController();
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
                    'redirect' => 'bar',
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' => 'http://localhost/signin/?email=foo&redirect=bar&stay-signed-in=1',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        'invalid-email',
                    ]
                ],
            ],
            'empty password' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'redirect' => 'bar',
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=bar&stay-signed-in=1',
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
                    'redirect' => 'bar',
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=public%40simplytestable.com&redirect=bar&stay-signed-in=1',
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
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignInSubmitActionAuthenticationFailure(
        array $httpFixtures,
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

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
    public function signInSubmitActionAuthenticationFailureDataProvider()
    {
        return [
            'user does not exist' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => 'bar',
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=bar&stay-signed-in=1',
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
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'redirect' => 'bar',
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=bar&stay-signed-in=1',
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
     * @param Request $request
     * @param PostmarkMessage $postmarkMessage
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignInSubmitActionResendConfirmationToken(
        array $httpFixtures,
        Request $request,
        PostmarkMessage $postmarkMessage,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $this->container->set('request', $request);

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction();

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
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
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                ]),
                'postmarkMessage' => MockFactory::createPostmarkMessage([
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
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED,
                    ]
                ],
            ],
            'authentication success and user is not enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 404'),
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
                ],
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                ]),
                'postmarkMessage' => MockFactory::createPostmarkMessage([
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
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/signin/?email=user%40example.com&redirect=&stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_IN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signInSubmitActionSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param bool $expectedResponseHasUserCookie
     */
    public function testSignInSubmitActionSuccess(
        array $httpFixtures,
        Request $request,
        $expectedRedirectLocation,
        $expectedResponseHasUserCookie
    ) {
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $this->container->set('request', $request);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signInSubmitAction();

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());

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
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                ]),
                'expectedRedirectLocation' => 'http://localhost/',
                'expectedResponseHasUserCookie' => false,
            ],
            'stay-signed-in true' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'request' => new Request([], [
                    'email' => 'user@example.com',
                    'password' => 'foo',
                    'stay-signed-in' => true,
                ]),
                'expectedRedirectLocation' => 'http://localhost/',
                'expectedResponseHasUserCookie' => true,
            ],
        ];
    }
}
