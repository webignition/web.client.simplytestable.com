<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\ResetPassword;

use App\Controller\Action\User\ResetPasswordController as ResetPasswordActionController;
use App\Controller\View\User\ResetPasswordController;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ResetPasswordControllerTest extends AbstractViewControllerTest
{
    const CHOOSE_DEFAULT_VIEW_NAME = 'user-reset-password-choose.html.twig';
    const CHOOSE_INVALID_TOKEN_VIEW_NAME = 'user-reset-password-invalid-token.html.twig';
    const CHOOSE_ROUTE_NAME = 'view_user_reset_password_choose';
    const REQUEST_VIEW_NAME = 'user-reset-password.html.twig';
    const REQUEST_ROUTE_NAME = 'view_user_reset_password_request';

    const USER_EMAIL = 'user@example.com';
    const TOKEN = 'token-value';

    private $chooseActionRouteParameters = [
        'email' => self::USER_EMAIL,
        'token' => self::TOKEN,
    ];

    public function testIsIEFilteredChooseRoute()
    {
        $this->issueIERequest(self::CHOOSE_ROUTE_NAME, $this->chooseActionRouteParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIsIEFilteredRequestRoute()
    {
        $this->issueIERequest(self::REQUEST_ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testChooseActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse(self::TOKEN),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::CHOOSE_ROUTE_NAME, $this->chooseActionRouteParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider chooseActionRenderDataProvider
     */
    public function testChooseActionRender(
        array $httpFixtures,
        array $flashBagMessages,
        Request $request,
        string $token,
        Twig_Environment $twig
    ) {
        $userManager = self::$container->get(UserManager::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $flashBag->setAll($flashBagMessages);

        /* @var ResetPasswordController $resetPasswordController */
        $resetPasswordController = self::$container->get(ResetPasswordController::class);
        $this->setTwigOnController($twig, $resetPasswordController);

        $response = $resetPasswordController->chooseAction($request, self::USER_EMAIL, $token);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function chooseActionRenderDataProvider(): array
    {
        return [
            'invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagMessages' => [],
                'request' => new Request(),
                'token' => 'invalid-token',
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::CHOOSE_INVALID_TOKEN_VIEW_NAME, $viewName);
                            $this->assertCommonChooseViewData($parameters);

                            $this->assertEquals('invalid-token', $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'invalid token, has password reset error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagMessages' => [
                    ResetPasswordActionController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                ],
                'request' => new Request(),
                'token' => 'invalid-token',
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::CHOOSE_INVALID_TOKEN_VIEW_NAME, $viewName);
                            $this->assertCommonChooseViewData($parameters);

                            $this->assertEquals('invalid-token', $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token, has password reset error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagMessages' => [
                    ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordActionController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                    ]
                ],
                'request' => new Request(),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::CHOOSE_DEFAULT_VIEW_NAME, $viewName);
                            $this->assertCommonChooseViewData($parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertEquals(
                                ResetPasswordActionController::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT,
                                $parameters['user_reset_password_error']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagMessages' => [],
                'request' => new Request(),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::CHOOSE_DEFAULT_VIEW_NAME, $viewName);
                            $this->assertCommonChooseViewData($parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertNull($parameters['user_reset_password_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'valid token, stay-signed-in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(self::TOKEN),
                ],
                'flashBagMessages' => [],
                'request' => new Request([
                    'stay-signed-in' => 1,
                ]),
                'token' => self::TOKEN,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::CHOOSE_DEFAULT_VIEW_NAME, $viewName);
                            $this->assertCommonChooseViewData($parameters);

                            $this->assertEquals(self::TOKEN, $parameters['token']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);
                            $this->assertNull($parameters['user_reset_password_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    public function testChooseActionCachedResponse()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse(self::TOKEN),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ResetPasswordController $resetPasswordController */
        $resetPasswordController = self::$container->get(ResetPasswordController::class);

        $response = $resetPasswordController->chooseAction(
            $request,
            self::USER_EMAIL,
            self::TOKEN
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $resetPasswordController->chooseAction(
            $newRequest,
            self::USER_EMAIL,
            self::TOKEN
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate(self::REQUEST_ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider requestActionRenderDataProvider
     */
    public function testRequestActionRender(array $flashBagMessages, Request $request, Twig_Environment $twig)
    {
        $session = self::$container->get(SessionInterface::class);
        $session->start();

        $flashBag = self::$container->get(FlashBagInterface::class);
        $flashBag->setAll($flashBagMessages);

        /* @var ResetPasswordController $resetPasswordController */
        $resetPasswordController = self::$container->get(ResetPasswordController::class);
        $this->setTwigOnController($twig, $resetPasswordController);

        $response = $resetPasswordController->requestAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function requestActionRenderDataProvider(): array
    {
        return [
            'no email' => [
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertRequestCommonViewData($viewName, $parameters);

                            $this->assertEquals('', $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has email' => [
                'flashBagMessages' => [],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertRequestCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has user_reset_password_error' => [
                'flashBagMessages' => [
                    ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_KEY => [
                        ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                    ]
                ],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertRequestCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertEquals(
                                ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK,
                                $parameters['user_reset_password_error']
                            );
                            $this->assertArrayNotHasKey('user_reset_password_confirmation', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has user_reset_password_confirmation' => [
                'flashBagMessages' => [
                    ResetPasswordActionController::FLASH_BAG_REQUEST_SUCCESS_KEY => [
                        ResetPasswordActionController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                    ]
                ],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertRequestCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertArrayNotHasKey('user_reset_password_error', $parameters);
                            $this->assertEquals(
                                ResetPasswordActionController::FLASH_BAG_REQUEST_MESSAGE_SUCCESS,
                                $parameters['user_reset_password_confirmation']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ResetPasswordController $resetPasswordController */
        $resetPasswordController = self::$container->get(ResetPasswordController::class);

        $response = $resetPasswordController->requestAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $resetPasswordController->requestAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertCommonChooseViewData(array $parameters)
    {
        $this->assertChooseViewParameterKeys($parameters);

        $this->assertEquals(self::USER_EMAIL, $parameters['email']);
    }

    private function assertChooseViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'email',
                'token',
                'stay_signed_in',
                'user_reset_password_error',
            ],
            array_keys($parameters)
        );
    }

    private function assertRequestCommonViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::REQUEST_VIEW_NAME, $viewName);
        $this->assertRequestViewParameterKeys($parameters);
    }

    private function assertRequestViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'email',
            ],
            array_keys($parameters)
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
