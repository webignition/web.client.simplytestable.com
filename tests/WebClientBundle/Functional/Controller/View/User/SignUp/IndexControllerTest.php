<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\Action\User\UserController;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\IndexController;
use SimplyTestable\WebClientBundle\Model\Coupon;
use SimplyTestable\WebClientBundle\Model\User\Plan;
use SimplyTestable\WebClientBundle\Services\CouponService;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class IndexControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/SignUp/Index:index.html.twig';
    const ROUTE_NAME = 'view_user_signup_index_index';

    const USER_EMAIL = 'user@example.com';

    public function testIndexActionPublicUserGetRequest()
    {
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
     * @param array $flashBagValues
     * @param Request $request
     * @param Twig_Environment $twig
     * @param bool $expectedHasRedirectCookie
     */
    public function testIndexActionRender(
        array $flashBagValues,
        Request $request,
        Twig_Environment $twig,
        $expectedHasRedirectCookie
    ) {
        $session = $this->container->get('session');

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);
        $this->setTwigOnController($twig, $indexController);

        $response = $indexController->indexAction($request);
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

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'no request data' => [
                'flashBagValues' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertFalse($parameters['has_coupon']);

                            $this->assertArrayNotHasKey('user_create_error', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('coupon', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'invalid plan' => [
                'flashBagValues' => [],
                'request' => new Request([
                    'plan' => 'foo',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertFalse($parameters['has_coupon']);

                            $this->assertArrayNotHasKey('user_create_error', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('coupon', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'request has email, plan, redirect' => [
                'flashBagValues' => [],
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
                            $this->assertEquals('agency', $parameters['plan']);
                            $this->assertEquals('foo', $parameters['redirect']);
                            $this->assertFalse($parameters['has_coupon']);

                            $this->assertArrayNotHasKey('user_create_error', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
                            $this->assertArrayNotHasKey('coupon', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => true,
            ],
            'user_create_error and user_create_confirmation' => [
                'flashBagValues' => [
                    UserController::FLASH_BAG_SIGN_UP_ERROR_KEY =>
                        UserController::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID,
                    UserController::FLASH_BAG_SIGN_UP_SUCCESS_KEY =>
                        UserController::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertFalse($parameters['has_coupon']);

                            $this->assertEquals('invalid-email', $parameters['user_create_error']);
                            $this->assertEquals('user-created', $parameters['user_create_confirmation']);
                            $this->assertArrayNotHasKey('coupon', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
                'expectedHasRedirectCookie' => false,
            ],
            'has coupon' => [
                'flashBagValues' => [],
                'request' => new Request([], [], [], [
                    CouponService::COUPON_COOKIE_NAME => 'TMS',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('personal', $parameters['plan']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertTrue($parameters['has_coupon']);

                            $this->assertArrayNotHasKey('user_create_error', $parameters);
                            $this->assertArrayNotHasKey('user_create_confirmation', $parameters);
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

        $this->container->get('request_stack')->push($request);

        /* @var IndexController $indexController */
        $indexController = $this->container->get(IndexController::class);

        $response = $indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $indexController->indexAction($newRequest);

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

        $this->assertInternalType('array', $parameters['plans']);

        /* @var Plan[] $plans */
        $plans = $parameters['plans'];
        foreach ($plans as $plan) {
            $this->assertInstanceOf(Plan::class, $plan);
            $this->assertNotEmpty($plan->getPrice());
        }
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
                'plan',
                'redirect',
                'has_coupon',
                'plans',
            ],
            array_keys($parameters)
        );
    }

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME);
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
