<?php

namespace App\Tests\Functional\Controller\Action\User\Account\EmailChange;

use App\Services\Mailer;
use App\Tests\Factory\PostmarkExceptionFactory;
use Postmark\Models\PostmarkException;
use App\Controller\Action\User\Account\EmailChangeController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Tests\Factory\PostmarkHttpResponseFactory;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use webignition\SimplyTestableUserModel\User;

class EmailChangeControllerRequestActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_request';
    const CURRENT_EMAIL = 'user@example.com';
    const NEW_EMAIL = 'new-email@example.com';
    const CONFIRMATION_TOKEN = 'email-change-request-token';
    const EXPECTED_REDIRECT_URL = '/account/';

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

        $this->user = new User(self::CURRENT_EMAIL);
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
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
            HttpResponseFactory::createSuccessResponse(),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME)
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
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    public function testRequestActionBadRequest(Request $request, array $expectedFlashBagValues)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->user);

        $response = $this->callRequestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
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
     * @param array $httpFixtures
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testRequestActionCreateFailure(array $httpFixtures, array $expectedFlashBagValues)
    {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $response = $this->callRequestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    /**
     * @return array
     */
    public function requestActionCreateFailureDataProvider()
    {
        return [
            'email taken' => [
                'httpFixtures' => [
                    HttpResponseFactory::createConflictResponse()
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
                    HttpResponseFactory::createInternalServerErrorResponse(),
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
     * @param PostmarkException $postmarkException
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testRequestActionSendConfirmationTokenFailure(
        PostmarkException $postmarkException,
        array $expectedFlashBagValues
    ) {
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendEmailChangeConfirmationToken')
            ->withArgs([
                self::NEW_EMAIL,
                self::CURRENT_EMAIL,
                self::CONFIRMATION_TOKEN,
            ])
            ->andThrow($postmarkException);

        $response = $this->callRequestAction($request, $mailer);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $flashBag->peekAll());
    }

    /**
     * @return array
     */
    public function requestActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkException' => PostmarkExceptionFactory::create(405),
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
                'postmarkException' => PostmarkExceptionFactory::create(406),
                'expectedFlashBagValues' => [
                    EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                        EmailChangeController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ],
                    EmailChangeController::FLASH_BAG_EMAIL_VALUE_KEY => [
                        self::NEW_EMAIL,
                    ],
                ],
            ],
            'postmark invalid email address' => [
                'postmarkException' => PostmarkExceptionFactory::create(300),
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
                'postmarkException' => PostmarkExceptionFactory::create(206),
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
        $flashBag = self::$container->get(FlashBagInterface::class);
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $mailer = \Mockery::mock(Mailer::class);
        $mailer
            ->shouldReceive('sendEmailChangeConfirmationToken')
            ->withArgs([
                self::NEW_EMAIL,
                self::CURRENT_EMAIL,
                self::CONFIRMATION_TOKEN,
            ]);

        $response = $this->callRequestAction($request, $mailer);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                EmailChangeController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
            ],
        ], $flashBag->peekAll());
    }

    /**
     * @param Request $request
     * @param Mailer $mailer
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    private function callRequestAction(Request $request, Mailer $mailer = null)
    {
        $mailer = (empty($mailer))
            ? \Mockery::mock(Mailer::class)
            : $mailer;

        return $this->emailChangeController->requestAction($mailer, $request);
    }
}
