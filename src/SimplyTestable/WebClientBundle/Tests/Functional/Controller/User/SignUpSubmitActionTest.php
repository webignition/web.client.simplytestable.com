<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class SignUpSubmitActionTest extends AbstractUserControllerTest
{
    public function testSignUpSubmitActionPostRequest()
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');

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

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('sign_up_submit');

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'plan' => 'basic',
                'email' => 'user@example.com',
                'password' => 'password',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            'http://localhost/signup/confirm/user@example.com/',
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider signUpSubmitActionBadRequestDataProvider
     *
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignUpSubmitActionBadRequest(
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');

        $this->container->set('request', $request);
        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpSubmitAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signUpSubmitActionBadRequestDataProvider()
    {
        return [
            'empty email' => [
                'request' => new Request([], [
                    'plan' => 'basic',
                ]),
                'expectedRedirectLocation' => 'http://localhost/signup/?plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_BLANK,
                    ]
                ],
            ],
            'invalid email' => [
                'request' => new Request([], [
                    'plan' => 'basic',
                    'email' => 'foo',
                ]),
                'expectedRedirectLocation' => 'http://localhost/signup/?email=foo&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID,
                    ]
                ],
            ],
            'empty password' => [
                'request' => new Request([], [
                    'plan' => 'basic',
                    'email' => 'user@example.com',
                ]),
                'expectedRedirectLocation' => 'http://localhost/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    'user_create_prefil' => [
                        'user@example.com',
                    ],
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_PASSWORD_BLANK,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpSubmitActionUserCreationFailureDataProvider
     *
     * @param Response[] $httpFixtures
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignUpSubmitActionUserCreationFailure(
        array $httpFixtures,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');

        $httpClient = $httpClientService->get();

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $this->container->set('request', $request);
        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpSubmitAction($request);

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
                    Response::fromMessage('HTTP/1.1 302'),
                ],
                'expectedRedirectLocation' => 'http://localhost/signup/?email=user%40example.com',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_SUCCESS_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS,
                    ]
                ],
            ],
            'failed due to read-only' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 503'),
                ],
                'expectedRedirectLocation' => 'http://localhost/signup/?email=user%40example.com&plan=basic',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY,
                    ]
                ],
            ],
            'failed, unknown' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedRedirectLocation' => 'http://localhost/signup/?email=user%40example.com&plan=basic',
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
     */
    public function testSignUpSubmitActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $httpClient = $httpClientService->get();

        $httpFixtures = [
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ];

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

        $request = new Request([], [
            'plan' => 'basic',
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $this->container->set('request', $request);

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpSubmitAction($request);

        $this->assertEquals(
            'http://localhost/signup/?email=user%40example.com&plan=basic',
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
                            'ErrorCode' => 405,
                            'Message' => 'foo',
                        ]),
                    ],
                ]),
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
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
                            'ErrorCode' => 406,
                            'Message' => 'foo',
                        ]),
                    ],
                ]),
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'invalid email address' => [
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
                            'ErrorCode' => 300,
                            'Message' => 'foo',
                        ]),
                    ],
                ]),
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
     */
    public function testSignUpSubmitActionSuccess(Request $request, array $couponData)
    {
        $session = $this->container->get('session');
        $httpClientService = $this->container->get('simplytestable.services.httpclientservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $couponService = $this->container->get('simplytestable.services.couponservice');

        $httpClient = $httpClientService->get();

        $httpFixtures = [
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ];

        $httpMockPlugin = new MockPlugin($httpFixtures);
        $httpClient->addSubscriber($httpMockPlugin);

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

        $couponService->setCouponData($couponData);

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpSubmitAction($request);

        $this->assertEquals(
            'http://localhost/signup/confirm/user@example.com/',
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
                    'email' => 'user@example.com',
                    'password' => 'password',
                ]),
                'couponData' => [],
            ],
            'with active coupon' => [
                'request' => new Request(
                    [],
                    [
                        'plan' => 'basic',
                        'email' => 'user@example.com',
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
                    'email' => 'user@example.com',
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
}
