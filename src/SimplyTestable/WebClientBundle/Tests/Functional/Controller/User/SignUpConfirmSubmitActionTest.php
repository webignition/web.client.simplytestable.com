<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use Guzzle\Http\Message\Response;
use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class SignUpConfirmSubmitActionTest extends AbstractUserControllerTest
{
    const USER_EMAIL = 'user@example.com';
    const TOKEN = 'confirmation-token';
    const PASSWORD = 'password-value';

    public function testSignUpConfirmSubmitActionPostRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n\"confirmation-token-here\""),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate('sign_up_confirm_submit', [
            'email' => self::USER_EMAIL,
        ]);

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'token' => self::TOKEN,
                'password' => self::PASSWORD,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/signin/?email=user%40example.com', $response->getTargetUrl());
    }

    /**
     * @dataProvider signUpConfirmSubmitActionFailureDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param $email
     * @param string $expectedRedirectLocation
     * @param array $expectedFlashBagValues
     */
    public function testSignUpConfirmSubmitActionFailure(
        array $httpFixtures,
        Request $request,
        $email,
        $expectedRedirectLocation,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');

        $this->setHttpFixtures($httpFixtures);

        $this->userController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpConfirmSubmitAction($request, $email);

        $this->assertEquals($expectedRedirectLocation, $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function signUpConfirmSubmitActionFailureDataProvider()
    {
        /**
        self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY,
        self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_BLANK
         */

        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'request' => new Request(),
                'email' => 'foo@example.com',
                'expectedRedirectLocation' => 'http://localhost/signup/confirm/foo@example.com/',
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
                'expectedRedirectLocation' => 'http://localhost/signup/confirm/user@example.com/',
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
                ],
                'request' => new Request([], [
                    'token' => self::TOKEN,
                ]),
                'email' => self::USER_EMAIL,
                'expectedRedirectLocation' => 'http://localhost/signup/confirm/user@example.com/',
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
                ],
                'request' => new Request([], [
                    'token' => 'invalid token',
                ]),
                'email' => self::USER_EMAIL,
                'expectedRedirectLocation' => 'http://localhost/signup/confirm/user@example.com/',
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
     */
    public function testSignUpConfirmSubmitActionSuccess(array $requestCookies, $expectedRedirectUrl)
    {
        $session = $this->container->get('session');

        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userController->setContainer($this->container);

        $request = new Request([], [
            'token' => self::TOKEN,
        ]);

        if (!empty($requestCookies)) {
            $request->cookies->replace($requestCookies);
        }

        /* @var RedirectResponse $response */
        $response = $this->userController->signUpConfirmSubmitAction($request, self::USER_EMAIL);

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
                'expectedRedirectUrl' => 'http://localhost/signin/?email=user%40example.com',
            ],
            'has redirect cookie' => [
                'requestCookies' => [
                    'simplytestable-redirect' => 'foo',
                ],
                'expectedRedirectUrl' => 'http://localhost/signin/?email=user%40example.com&redirect=foo',
            ],
        ];
    }
}
