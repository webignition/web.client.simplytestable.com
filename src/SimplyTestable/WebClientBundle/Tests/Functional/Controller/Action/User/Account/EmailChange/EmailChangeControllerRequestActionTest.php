<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class EmailChangeControllerRequestActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_request';
    const NEW_EMAIL = 'new-email@example.com';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/';

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->user = new User('user@example.com');
    }

    /**
     * {@inheritdoc}
     */
    public function postRequestPublicUserDataProvider()
    {
        return [
            'default' => [
                'routeName' => self::ROUTE_NAME,
            ],
        ];
    }

    public function testRequestActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
            self::NEW_EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                    'token' => 'email-change-request-token',
                    'new_email' => self::NEW_EMAIL,
                ])),
        ]);

        $user = new User('user@example.com', 'password');

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider requestActionBadRequestDataProvider
     *
     * @param Request $request
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    public function testRequestActionBadRequest(Request $request, array $expectedFlashBagValues)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');

        $userService->setUser($this->user);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
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
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY,
                    ],
                ],
            ],
            'same email' => [
                'request' => new Request([], [
                    'email' => 'user@example.com',
                ]),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME,
                    ],
                ],
            ],
            'invalid email' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        'foo'
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider requestActionCreateFailureDataProvider
     *
     * @param Response[] $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    public function testRequestActionCreateFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');

        $userService->setUser($this->user);

        $this->setHttpFixtures($httpFixtures);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function requestActionCreateFailureDataProvider()
    {
        return [
            'email taken' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 409'),
                ],
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
            'unknown' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider requestActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    public function testRequestActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $userService->setUser($this->user);
        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                'token' => 'email-change-request-token',
                'new_email' => self::NEW_EMAIL,
            ])),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function requestActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 405,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 406,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
            'postmark invaild email address' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 300,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
            'postmark unknown error' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
                    self::NEW_EMAIL,
                    [
                        'ErrorCode' => 206,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
        ];
    }

    public function testRequestActionSuccess()
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $userService->setUser($this->user);
        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockConfirmEmailAddressPostmarkMessage(
            self::NEW_EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                'token' => 'email-change-request-token',
                'new_email' => self::NEW_EMAIL,
            ])),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->emailChangeController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                EmailChangeController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
            ],
        ], $session->getFlashBag()->peekAll());
    }
}
