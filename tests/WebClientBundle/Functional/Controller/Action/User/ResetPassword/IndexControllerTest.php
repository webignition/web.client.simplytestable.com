<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\PostmarkSender;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockPostmarkMessageFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Tests\WebClientBundle\Helper\MockeryArgumentValidator;
use Tests\WebClientBundle\Services\HttpMockHandler;

class IndexControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'action_user_resetpassword_index_request';
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = $this->container->get(IndexController::class);
        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
    }

    public function testRequestActionPostRequest()
    {
        $mailService = $this->container->get(MailService::class);

        $postmarkMessage = MockPostmarkMessageFactory::createMockResetPasswordPostmarkMessage(
            self::EMAIL,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ]
        );

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
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
            '/reset-password/?email=user%40example.com',
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
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testRequestActionBadRequest(Request $request, array $expectedFlashBagValues, $expectedRedirectUrl)
    {
        $session = $this->container->get('session');

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction($request);

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
                'expectedRedirectUrl' => '/reset-password/',
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
                'expectedRedirectUrl' => '/reset-password/?email=foo',
            ],
        ];
    }

    /**
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testRequestActionUserDoesNotExist()
    {
        $session = $this->container->get('session');

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
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
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testRequestActionInvalidAdminCredentials()
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
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
        $response = $this->indexController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
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
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function testRequestActionSendConfirmationTokenFailure(
        PostmarkMessage $postmarkMessage,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $postmarkSender = $this->container->get(PostmarkSender::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $request = new Request([], [
            'email' => self::EMAIL,
        ]);

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
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

    public function testRequestActionMessageConfirmationUrl()
    {
        $postmarkMessage = MockPostmarkMessageFactory::createMockPostmarkMessage(
            self::EMAIL,
            MockPostmarkMessageFactory::SUBJECT_RESET_YOUR_PASSWORD,
            [
                'ErrorCode' => 0,
                'Message' => 'OK',
            ],
            [
                'with' => \Mockery::on(MockeryArgumentValidator::stringContains([
                    sprintf(
                        'http://localhost/reset-password/%s/%s/',
                        self::EMAIL,
                        self::CONFIRMATION_TOKEN
                    )
                ])),
            ]
        );

        $mailService = $this->container->get(MailService::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
        ]);

        $mailService->setPostmarkMessage($postmarkMessage);

        $this->indexController->requestAction(new Request([], [
            'email' => self::EMAIL,
        ]));
    }

    public function testResendActionSuccess()
    {
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $postmarkSender = $this->container->get(PostmarkSender::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
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

        /* @var RedirectResponse $response */
        $response = $this->indexController->requestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/reset-password/?email=user%40example.com', $response->getTargetUrl());
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
