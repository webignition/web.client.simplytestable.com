<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\SignUp;

use App\Controller\Action\User\UserController;
use App\Controller\View\User\SignUp\ConfirmController;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Services\SymfonyRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Action\SignUp\User\ConfirmController as ActionConfirmController;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ConfirmControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'user-sign-up-confirm.html.twig';
    const ROUTE_NAME = 'view_user_sign_up_confirm';
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'email' => self::USER_EMAIL,
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagMessages,
        Request $request,
        Twig_Environment $twig
    ) {
        $userManager = self::$container->get(UserManager::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $flashBag->setAll($flashBagMessages);

        /* @var ConfirmController $confirmController */
        $confirmController = self::$container->get(ConfirmController::class);
        $this->setTwigOnController($twig, $confirmController);

        $response = $confirmController->indexAction($request, self::USER_EMAIL);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'user does not exist' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertTrue($parameters['has_notification']);
                            $this->assertArrayHasKey('user_error', $parameters);
                            $this->assertEquals('invalid-user', $parameters['user_error']);

                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('', $parameters['token']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'exists, no flash messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertFalse($parameters['has_notification']);
                            $this->assertArrayNotHasKey('user_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('', $parameters['token']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'exists, no flash messages, has token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [],
                'request' => new Request([
                    'token' => 'foo',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertFalse($parameters['has_notification']);
                            $this->assertArrayNotHasKey('user_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('foo', $parameters['token']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'confirmation sent' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY => [
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE,
                    ],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertTrue($parameters['has_notification']);
                            $this->assertArrayNotHasKey('user_error', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('', $parameters['token']);
                            $this->assertEquals('sent', $parameters['token_resend_confirmation']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'user created' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [
                    UserController::FLASH_SIGN_UP_SUCCESS_KEY => [
                        UserController::FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED
                    ],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertTrue($parameters['has_notification']);
                            $this->assertArrayNotHasKey('user_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('', $parameters['token']);
                            $this->assertEquals('user-created', $parameters['user_create_confirmation']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has flash error messages' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [
                    'user_token_error' => ['blank-token'],
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                    ],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertTrue($parameters['has_notification']);
                            $this->assertArrayNotHasKey('user_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);

                            $this->assertEquals('', $parameters['token']);
                            $this->assertEquals(
                                'postmark-not-allowed-to-send',
                                $parameters['token_resend_error']
                            );
                            $this->assertEquals('blank-token', $parameters['user_token_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'token_resend_error=invalid-user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'flashBagMessages' => [
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY => [
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID,
                    ],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertTrue($parameters['has_notification']);
                            $this->assertArrayNotHasKey('token_resend_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('user_token_error', $parameters);
                            $this->assertArrayNotHasKey('token_resend_error', $parameters);

                            $this->assertEquals('', $parameters['token']);
                            $this->assertEquals('invalid-user', $parameters['user_error']);

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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ConfirmController $confirmController */
        $confirmController = self::$container->get(ConfirmController::class);

        $response = $confirmController->indexAction(
            $request,
            self::USER_EMAIL
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

        $newResponse = $confirmController->indexAction(
            $newRequest,
            self::USER_EMAIL
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertCommonViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertEquals(self::USER_EMAIL, $parameters['email']);
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'email',
            'token',
            'has_notification',
        ];

        $keys = array_keys($parameters);
        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $keys);
        }
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
