<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
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
        $this->setHttpFixtures([
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
        $this->assertEquals('http://localhost/', $response->getTargetUrl());
    }

    /**
     * @dataProvider resetPasswordChooseDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     * @param bool $expectedResponseHasUserCookie
     */
    public function testResetPasswordChooseSubmitAction(
        array $httpFixtures,
        Request $request,
        $expectedRedirectLocation,
        array $expectedFlashBagValues,
        $expectedResponseHasUserCookie
    ) {
        $session = $this->container->get('session');

        $this->setHttpFixtures($httpFixtures);

        $this->userController->setContainer($this->container);

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
                'expectedRedirectLocation' => 'http://localhost/reset-password/',
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
                'expectedRedirectLocation' => 'http://localhost/reset-password/',
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
                'expectedRedirectLocation' => 'http://localhost/reset-password/',
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
                'expectedRedirectLocation' => 'http://localhost/reset-password/',
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
                'expectedRedirectLocation' => 'http://localhost/reset-password/user@example.com/foo/?stay-signed-in=0',
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
                    'http://localhost/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
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
                ],
                'request' => new Request([], [
                    'email' => self::USER_EMAIL,
                    'token' => self::TOKEN,
                    'password' => self::PASSWORD,
                ]),
                'expectedRedirectLocation' =>
                    'http://localhost/reset-password/user@example.com/confirmation-token/?stay-signed-in=0',
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
                'expectedRedirectLocation' => 'http://localhost/',
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
                'expectedRedirectLocation' => 'http://localhost/',
                'expectedFlashBagValues' => [],
                'expectedResponseHasUserCookie' => true,
            ],
        ];
    }
}
