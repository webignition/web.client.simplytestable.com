<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\User;

use AppBundle\Controller\Action\User\UserController;
use AppBundle\Exception\InvalidAdminCredentialsException;
use AppBundle\Services\ResqueQueueService;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SignUpConfirmSubmitActionTest extends AbstractUserControllerTest
{
    const USER_EMAIL = 'user@example.com';
    const TOKEN = 'confirmation-token';
    const PASSWORD = 'password-value';

    public function testSignUpConfirmSubmitActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse('confirmation-token-here'),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate('sign_up_confirm_submit', [
                'email' => self::USER_EMAIL,
            ]),
            [
                'token' => self::TOKEN,
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/signin/?email=user%40example.com', $response->getTargetUrl());
    }

    /**
     * @dataProvider signUpConfirmSubmitActionFailureDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param $email
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     *
     * @throws InvalidAdminCredentialsException
     * @throws \CredisException
     * @throws \Exception
     */
    public function testSignUpConfirmSubmitActionFailure(
        array $httpFixtures,
        Request $request,
        $email,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->callSignUpConfirmSubmitAction($request, $email);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signUpConfirmSubmitActionFailureDataProvider()
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request(),
                'email' => 'foo@example.com',
                'expectedRedirectLocation' => '/signup/confirm/foo@example.com/',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_MESSAGE_USER_INVALID,
                    ]
                ],
            ],
            'empty token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'token' => '',
                ]),
                'email' => self::USER_EMAIL,
                'expectedRedirectLocation' => '/signup/confirm/user@example.com/',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_BLANK,
                    ]
                ],
            ],
            'failed, read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'request' => new Request([], [
                    'token' => self::TOKEN,
                ]),
                'email' => self::USER_EMAIL,
                'expectedRedirectLocation' => '/signup/confirm/user@example.com/',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_FAILED_READ_ONLY,
                    ]
                ],
            ],
            'failed, invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createBadRequestResponse(),
                    HttpResponseFactory::createBadRequestResponse(),
                    HttpResponseFactory::createBadRequestResponse(),
                    HttpResponseFactory::createBadRequestResponse(),
                ],
                'request' => new Request([], [
                    'token' => 'invalid token',
                ]),
                'email' => self::USER_EMAIL,
                'expectedRedirectLocation' => '/signup/confirm/user@example.com/',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY => [
                        UserController::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_INVALID,
                    ]
                ],
            ],
        ];
    }

    /**
     * @dataProvider signUpConfirmSubmitActionSuccessDataProvider
     *
     * @param array $requestCookies
     * @param string $expectedRedirectUrl
     *
     * @throws \CredisException
     * @throws \Exception
     */
    public function testSignUpConfirmSubmitActionSuccess(array $requestCookies, $expectedRedirectUrl)
    {
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request([], [
            'token' => self::TOKEN,
        ]);

        if (!empty($requestCookies)) {
            $request->cookies->replace($requestCookies);
        }

        $response = $this->callSignUpConfirmSubmitAction($request, self::USER_EMAIL);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
        $this->assertEquals([
            'user_signin_confirmation' => [
                'user-activated',
            ],
        ], $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signUpConfirmSubmitActionSuccessDataProvider()
    {
        return [
            'without redirect cookie' => [
                'requestCookies' => [],
                'expectedRedirectUrl' => '/signin/?email=user%40example.com',
            ],
            'has redirect cookie' => [
                'requestCookies' => [
                    'simplytestable-redirect' => 'foo',
                ],
                'expectedRedirectUrl' => '/signin/?email=user%40example.com&redirect=foo',
            ],
        ];
    }

    /**
     * @param Request $request
     * @param string $email
     *
     * @return RedirectResponse
     *
     * @throws InvalidAdminCredentialsException
     * @throws \CredisException
     * @throws \Exception
     */
    private function callSignUpConfirmSubmitAction(Request $request, $email)
    {
        return $this->userController->signUpConfirmSubmitAction(
            self::$container->get(ResqueQueueService::class),
            $request,
            $email
        );
    }
}
