<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\SignUp\User;

use Psr\Http\Message\ResponseInterface;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\User\ConfirmController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\PostmarkHttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Tests\WebClientBundle\Services\HttpMockHandler;
use Tests\WebClientBundle\Services\PostmarkMessageVerifier;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class ConfirmControllerTest extends AbstractBaseTestCase
{
    const EMAIL = 'user@example.com';
    const CONFIRMATION_TOKEN = 'confirmation-token-here';
    const EXPECTED_REDIRECT_URL = '/signup/confirm/'. self::EMAIL .'/';

    /**
     * @var ConfirmController
     */
    private $confirmController;

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

        $this->confirmController = $this->container->get(ConfirmController::class);
        $this->httpMockHandler = $this->container->get(HttpMockHandler::class);
    }

    public function testResendActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
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

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
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

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);


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
     * @param ResponseInterface $postmarkHttpResponse
     * @param array $expectedFlashBagValues
     *
     * @throws MailConfigurationException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testResendActionSendConfirmationTokenFailure(
        ResponseInterface $postmarkHttpResponse,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $httpHistoryContainer = $this->container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = $this->container->get(PostmarkMessageVerifier::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            $postmarkHttpResponse,
        ]);

        /* @var RedirectResponse $response */
        $response = $this->confirmController->resendAction(self::EMAIL);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        $lastRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($lastRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);
    }

    /**
     * @return array
     */
    public function resendActionSendConfirmationTokenFailureDataProvider()
    {
        return [
            'postmark not allowed to send to user email' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(405),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ]
                ],
            ],
            'postmark inactive recipient' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(406),
                'expectedFlashBagValues' => [
                    ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
            ],
            'postmark unknown error' => [
                'postmarkHttpResponse' => PostmarkHttpResponseFactory::createErrorResponse(300),
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
        $httpHistoryContainer = $this->container->get(HttpHistoryContainer::class);
        $postmarkMessageVerifier = $this->container->get(PostmarkMessageVerifier::class);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::CONFIRMATION_TOKEN),
            PostmarkHttpResponseFactory::createSuccessResponse(),
        ]);

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

        $lastRequest = $httpHistoryContainer->getLastRequest();

        $isPostmarkMessageResult = $postmarkMessageVerifier->isPostmarkRequest($lastRequest);
        $this->assertTrue($isPostmarkMessageResult, $isPostmarkMessageResult);

        $verificationResult = $postmarkMessageVerifier->verify(
            [
                'From' => 'robot@simplytestable.com',
                'To' => self::EMAIL,
                'Subject' => '[Simply Testable] Activate your account',
                'TextBody' => [
                    sprintf(
                        'http://localhost/signup/confirm/%s/?token=%s',
                        self::EMAIL,
                        self::CONFIRMATION_TOKEN
                    ),
                ],
            ],
            $httpHistoryContainer->getLastRequest()
        );

        $this->assertTrue($verificationResult, $verificationResult);
    }
}
