<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\User;

use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordChooseSubmitActionTest extends AbstractUserControllerTest
{
    const USER_EMAIL = 'user@example.com';
    const TOKEN = 'confirmation-token';
    const PASSWORD = 'password-value';

    public function testResetPasswordChooseSubmitActionPostRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(self::TOKEN),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('reset_password_choose_submit');

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'email' => self::USER_EMAIL,
                'token' => self::TOKEN,
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }

    /**
     * @dataProvider resetPasswordChooseDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     * @param bool $expectedResponseHasUserCookie
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testResetPasswordChooseSubmitAction(
        array $httpFixtures,
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues,
        $expectedResponseHasUserCookie
    ) {
        $session = $this->container->get('session');

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->userController->resetPasswordChooseSubmitAction($request);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());

        /* @var Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();

        if ($expectedResponseHasUserCookie) {
            $this->assertCount(1, $responseCookies);

            $responseCookie = $responseCookies[0];
            $this->assertEquals(UserManager::USER_COOKIE_KEY, $responseCookie->getName());
        } else {
            $this->assertEmpty($responseCookies);
        }
    }

    /**
     * @return array
     */
    public function resetPasswordChooseDataProvider()
    {
        return [
            'empty email' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => '',
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid email' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => 'foo',
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'empty token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => '',
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => 'foo',
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/reset-password/user@example.com/foo/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        UserController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_TOKEN_INVALID,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'empty password' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => '',
                ]),
                'expectedRedirectLocation' =>
                    '/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        UserController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_PASSWORD_BLANK,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'failed; HTTP 503' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' =>
                    '/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
                'expectedFlashBagValues' => [
                    UserController::FLASH_BAG_RESET_PASSWORD_ERROR_KEY => [
                        UserController::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY,
                    ],
                ],
                'expectedResponseHasUserCookie' => false,
            ],
            'success, stay-signed-in=0' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' => '/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => false,
            ],
            'success, stay-signed-in=1' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                    'stay-signed-in' => 1,
                ]),
                'expectedRedirectLocation' => '/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => true,
            ],
        ];
    }
}
