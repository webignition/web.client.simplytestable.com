<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\ResetPassword;

use Guzzle\Http\Message\Response;
use Mockery\Mock;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\User\ConfirmController;
use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockPostmarkMessageFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use Symfony\Component\HttpFoundation\Request;

class IndexControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'action_user_resetpassword_index_request';
    const EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
        $this->indexController->setContainer($this->container);
    }

    public function testRequestActionPostRequest()
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $postmarkMessage = MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
            self::EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        );

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'email' => self::EMAIL
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            'http://localhost/reset-password/?email=user%40example.com',
            $response->getTargetUrl()
        );
    }

    /**
     * @dataProvider requestActionBadRequestDataProvider
     *
     * @param Request $request
     * @param array $expectedFlashBagValues
     * @param string $expectedRedirectUrl
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     */
    public function testRequestActionBadRequest(Request $request, array $expectedFlashBagValues, $expectedRedirectUrl)
    {
        $session = $this->container->get('session');

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
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
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                    ],
                ],
                'expectedRedirectUrl' => 'http://localhost/reset-password/',
            ],
            'invalid email' => [
                'request' => new Request([], [
                    'email' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID,
                    ],
                ],
                'expectedRedirectUrl' => 'http://localhost/reset-password/?email=foo',
            ],
        ];
    }

    /**
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     */
    public function testRequestActionUserDoesNotExist()
    {
        $session = $this->container->get('session');

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->container->set('request', $request);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 404'),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
    }

    /**
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     */
    public function testRequestActionInvalidAdminCredentials()
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->container->set('request', $request);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockPostmarkMessage(
            'jon@simplytestable.com',
            'Invalid admin user credentials',
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
    }


    /**
     * @dataProvider requestActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException
     */
    public function testRequestActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function requestActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 405,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 406,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                    ]
                ],
            ],
            'postmark invalid email' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 300,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL,
                    ]
                ],
            ],
            'postmark unknown error' => [
                'postmarkMessage' => MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
                    self::EMAIL,
                    [
                        'ErrorCode' => 303,
                        'Message' => 'foo',
                    ]
                ),
                'expectedFlashBagValues' => [
                    IndexController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        IndexController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN,
                    ]
                ],
            ],
        ];
    }

    public function testResendActionSuccess()
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $mailService->setPostmarkMessage(MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
            self::EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        ));

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->container->set('request', $request);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/reset-password/?email=user%40example.com', $response->getTargetUrl());
        $this->assertEquals(
            [
                IndexController::FLASH_BAG_REQUEST_SUCCESS_KEY => [
                    IndexController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                ]
            ],
            $session->getFlashBag()->peekAll()
        );

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }
}
