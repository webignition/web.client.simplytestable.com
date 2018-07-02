<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\EmailChange;

use Postmark\PostmarkClient;
use Psr\Http\Message\ResponseInterface;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\EmailChangeController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\Configuration\MailConfiguration;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Tests\WebClientBundle\Factory\PostmarkHttpResponseFactory;
use webignition\SimplyTestableUserModel\User;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class EmailChangeControllerRequestActionTest extends AbstractEmailChangeControllerTest
{
    const ROUTE_NAME = 'action_user_account_emailchange_request';
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
        $userManager = $this->container->get(UserManager::class);

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
            $this->createRequestUrl(self::ROUTE_NAME)
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
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);

        $response = $this->callRequestAction($request);

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
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $response = $this->callRequestAction($request);

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
     * @param ResponseInterface $postmarkHttpResponse
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function testRequestActionSendConfirmationTokenFailure(
        ResponseInterface $postmarkHttpResponse,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
            $postmarkHttpResponse,
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $response = $this->callRequestAction($request);

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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(406),
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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(300),
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
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(206),
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
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'token' => self::CONFIRMATION_TOKEN,
                'new_email' => self::NEW_EMAIL,
            ]),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([], [
            'email' => self::NEW_EMAIL,
        ]);

        $response = $this->callRequestAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals([
            EmailChangeController::FLASH_BAG_REQUEST_KEY => [
                EmailChangeController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
            ],
        ], $session->getFlashBag()->peekAll());

        $httpHistory = $this->container->get(HttpHistoryContainer::class);
        $lastMessageBody = json_decode($httpHistory->getLastRequest()->getBody()->getContents(), true);

        $this->assertContains(
            sprintf(
                'http://localhost/account/?token=%s',
                self::CONFIRMATION_TOKEN
            ),
            $lastMessageBody['TextBody']
        );
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    private function callRequestAction(Request $request)
    {
        return $this->emailChangeController->requestAction(
            $this->container->get(MailConfiguration::class),
            $this->container->get(PostmarkClient::class),
            $this->container->get('twig'),
            $request
        );
    }
}
