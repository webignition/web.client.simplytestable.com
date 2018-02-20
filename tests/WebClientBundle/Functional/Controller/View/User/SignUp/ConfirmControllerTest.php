<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\UserController;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\ConfirmController;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\User\ConfirmController as ActionConfirmController;

class ConfirmControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/SignUp/Confirm:index.html.twig';
    const ROUTE_NAME = 'view_user_signup_confirm_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var ConfirmController
     */
    private $confirmController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->confirmController = new ConfirmController();
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param array $flashBagValues
     * @param Request $request
     * @param EngineInterface $templatingEngine
     *
     * @throws InvalidAdminCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagValues,
        Request $request,
        EngineInterface $templatingEngine
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);

        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'SimplyTestable\WebClientBundle\Services\CacheValidatorService',
                UserService::class,
                'SimplyTestable\WebClientBundle\Services\FlashBagValues',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ]
        );

        $this->confirmController->setContainer($container);

        $response = $this->confirmController->indexAction($request, self::USER_EMAIL);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'user does not exist' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertFalse($parameters['has_notification']);
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
                'flashBagValues' => [],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'flashBagValues' => [],
                'request' => new Request([
                    'token' => 'foo',
                ]),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'flashBagValues' => [
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY =>
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE,
                ],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'flashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_SUCCESS_KEY =>
                        UserController::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED
                ],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'flashBagValues' => [
                    'user_token_error' => 'blank-token',
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY =>
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND,
                ],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'flashBagValues' => [
                    ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_KEY =>
                        ActionConfirmController::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID,
                ],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertFalse($parameters['has_notification']);
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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $request = new Request();

        $this->container->get('request_stack')->push($request);
        $this->confirmController->setContainer($this->container);

        $response = $this->confirmController->indexAction(
            $request,
            self::USER_EMAIL
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->confirmController->indexAction(
            $newRequest,
            self::USER_EMAIL
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertCommonViewData($viewName, $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertEquals(self::USER_EMAIL, $parameters['email']);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'email',
                'token',
            ],
            array_keys($parameters)
        );

        $this->assertArrayHasKey('has_notification', $parameters);
    }

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME, [
            'email' => self::USER_EMAIL,
        ]);
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
