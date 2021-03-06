<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\SignUp;

use App\Controller\Action\User\UserController;
use App\Controller\View\User\SignUp\RequestController;
use App\Model\Coupon;
use App\Model\User\Plan;
use App\Request\User\SignUpRequest;
use App\Services\CouponService;
use App\Services\Request\Validator\User\UserAccountRequestValidator;
use App\Tests\Factory\MockFactory;
use App\Tests\Services\SymfonyRequestFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;

class RequestControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'user-sign-up.html.twig';
    const ROUTE_NAME = 'view_user_sign_up_request';
    const USER_EMAIL = 'user@example.com';

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $flashBagMessages,
        Request $request,
        Twig_Environment $twig,
        bool $expectedHasRedirectCookie
    ) {
        $session = self::$container->get(SessionInterface::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $session->start();
        $flashBag->setAll($flashBagMessages);

        /* @var RequestController $requestController */
        $requestController = self::$container->get(RequestController::class);
        $this->setTwigOnController($twig, $requestController);

        $response = $requestController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);

        /* @var Cookie[] $responseCookies */
        $responseCookies = $response->headers->getCookies();

        if ($expectedHasRedirectCookie) {
            $this->assertNotEmpty($responseCookies);
            $redirectCookie = $responseCookies[0];

            $this->assertEquals('simplytestable-redirect', $redirectCookie->getName());
        } else {
            $this->assertEmpty($responseCookies);
        }
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no request data' => [
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'invalid plan' => [
                'flashBagMessages' => [],
                'request' => new Request([
                    'plan' => 'foo',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'request has email, plan, redirect' => [
                'flashBagMessages' => [],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                    'plan' => 'agency',
                    'redirect' => 'foo',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('agency', $parameters['plan']);
                            $this->assertEquals('foo', $parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => true,
            ],
            'invalid email with error_field and error_state' => [
                'flashBagMessages' => [
                    UserController::FLASH_SIGN_UP_ERROR_FIELD_KEY => [SignUpRequest::PARAMETER_EMAIL],
                    UserController::FLASH_SIGN_UP_ERROR_STATE_KEY => [UserAccountRequestValidator::STATE_INVALID],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('email', $parameters['error_field']);
                            $this->assertEquals('invalid', $parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'user_create_error' => [
                'flashBagMessages' => [
                    UserController::FLASH_SIGN_UP_ERROR_KEY => [
                        UserController::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY,
                    ],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEquals(
                                UserController::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY,
                                $parameters['user_create_error']
                            );
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'user_create_confirmation' => [
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

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEquals('user-created', $parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'has coupon' => [
                'flashBagMessages' => [],
                'request' => new Request([], [], [], [
                    CouponService::COUPON_COOKIE_NAME => 'TMS',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_create_error']);
                            $this->assertEmpty($parameters['user_create_confirmation']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertInstanceOf(Coupon::class, $parameters['coupon']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var RequestController $requestController */
        $requestController = self::$container->get(RequestController::class);

        $response = $requestController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

        $newResponse = $requestController->indexAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertCommonViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertIsArray($parameters['plans']);

        /* @var Plan[] $plans */
        $plans = $parameters['plans'];
        foreach ($plans as $plan) {
            $this->assertInstanceOf(Plan::class, $plan);
            $this->assertNotEmpty($plan->getPrice());
        }
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'error_field',
            'error_state',
            'user_create_error',
            'user_create_confirmation',
            'email',
            'plan',
            'redirect',
            'coupon',
            'selected_field',
            'plans',
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
