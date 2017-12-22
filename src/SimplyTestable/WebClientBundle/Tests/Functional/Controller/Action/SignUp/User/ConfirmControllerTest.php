<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\SignUp\User;

use Guzzle\Http\Message\Response;
use Mockery\Mock;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\User\ConfirmController;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class ConfirmControllerTest extends BaseSimplyTestableTestCase
{
    const EMAIL = 'user@example.com';
    const EXPECTED_REDIRECT_URL = 'http://localhost/signup/confirm/'. self::EMAIL .'/';

    /**
     * @var ConfirmController
     */
    private $confirmController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->confirmController = new ConfirmController();
        $this->confirmController->setContainer($this->container);
    }

    public function testResendActionPostRequest()
    {
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $postmarkMessage = $this->createMockActivateAccountPostmarkMessage([
            'ErrorCode' => 0,
            'Message' => 'OK',
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('action_signup_user_confirm_resend', [
            'email' => self::EMAIL,
        ]);

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

    public function testResendActionUserDoesNotExist()
    {
        $session = $this->container->get('session');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 404'),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
    }

    public function testResendActionInvalidAdminCredentials()
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $mailService->setPostmarkMessage(MockFactory::createPostmarkMessage([
            'setFrom' => true,
            'setSubject' => [
                'with' => 'Invalid admin user credentials',
            ],
            'setTextMessage' => true,
            'addTo' => [
                'with' => 'jon@simplytestable.com',
            ],
            'send' => [
                'return' => json_encode([
                    'ErrorCode' => 0,
                    'Message' => 'OK',
                ]),
            ],
        ]));

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
    }

    /**
     * @dataProvider resendActionSendConfirmationTokenFailureDataProvider
     *
     * @param PostmarkMessage $postmarkMessage
     * @param array $expectedFlashBagValues
     */
    public function testResendActionSendConfirmationTokenFailure(
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

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return array
     */
    public function resendActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkMessage' => $this->createMockActivateAccountPostmarkMessage([
                    'ErrorCode' => 405,
                    'Message' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkMessage' => $this->createMockActivateAccountPostmarkMessage([
                    'ErrorCode' => 406,
                    'Message' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'postmark unknown error' => [
                'postmarkMessage' => $this->createMockActivateAccountPostmarkMessage([
                    'ErrorCode' => 300,
                    'Message' => 'foo',
                ]),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN,
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

        $mailService->setPostmarkMessage($this->createMockActivateAccountPostmarkMessage([
            'ErrorCode' => 0,
            'Message' => 'OK',
        ]));

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals(
            [
                ConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE,
                ]
            ],
            $session->getFlashBag()->peekAll()
        );

        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    /**
     * @return Mock|PostmarkMessage
     */
    private function createMockActivateAccountPostmarkMessage($responseData)
    {
        return MockFactory::createPostmarkMessage([
            'setFrom' => true,
            'setSubject' => [
                'with' => '[Simply Testable] Activate your account',
            ],
            'setTextMessage' => true,
            'addTo' => [
                'with' => 'user@example.com',
            ],
            'send' => [
                'return' => json_encode($responseData),
            ],
        ]);
    }
}
