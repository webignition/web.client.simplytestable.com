<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\User;

use App\Controller\User\SignInController;
use App\Services\FlashBagValues;
use App\Services\UserManager;
use App\Services\ViewRenderService;
use App\Tests\Factory\MockFactory;
use App\Tests\Functional\Controller\AbstractControllerTest;
use ReflectionClass;
use SimplyTestable\PageCacheBundle\Services\CacheableResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class SignInControllerRenderActionTest extends AbstractControllerTest
{
    const VIEW_NAME = 'user-sign-in.html.twig';
    const ROUTE_NAME = 'sign_in_render';
    const USER_EMAIL = 'user@example.com';

    public function testRenderActionIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testRenderActionPublicUserGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    public function testRenderActionIsLoggedIn()
    {
        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        /* @var SignInController $signInController */
        $signInController = self::$container->get(SignInController::class);

        /* @var FlashBagValues $flashBagValues */
        $flashBagValues = \Mockery::mock(FlashBagValues::class);

        /* @var CacheableResponseFactory $cacheableResponseFactory */
        $cacheableResponseFactory = \Mockery::mock(CacheableResponseFactory::class);

        /* @var RedirectResponse $response */
        $response = $signInController->renderAction($flashBagValues, $cacheableResponseFactory, new Request());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/', $response->getTargetUrl());
    }

    /**
     * @dataProvider renderActionRenderDataProvider
     */
    public function testRenderActionRender(
        array $flashBagMessages,
        Request $request,
        Twig_Environment $twig
    ) {
        $session = self::$container->get(SessionInterface::class);
        $session->start();

        $flashBag = self::$container->get(FlashBagInterface::class);
        $flashBag->setAll($flashBagMessages);

        /* @var SignInController $signInController */
        $signInController = self::$container->get(SignInController::class);
        $this->setTwigInViewRenderService($twig);

        $signInController->renderAction(
            self::$container->get(FlashBagValues::class),
            self::$container->get(CacheableResponseFactory::class),
            $request
        );
    }

    public function renderActionRenderDataProvider(): array
    {
        return [
            'no request data, no flash error messages' => [
                'flashBagMessages' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_signin_error']);
                            $this->assertEmpty($parameters['user_signin_confirmation']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['stay_signed_in']);
                            $this->assertEquals('email', $parameters['selected_field']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'request has email, stay-signed-in, redirect' => [
                'flashBagMessages' => [],
                'request' => new Request([
                    'email' => self::USER_EMAIL,
                    'stay-signed-in' => 1,
                    'redirect' => 'foo',
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEquals(self::USER_EMAIL, $parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEmpty($parameters['user_signin_error']);
                            $this->assertEmpty($parameters['user_signin_confirmation']);
                            $this->assertEquals('foo', $parameters['redirect']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);
                            $this->assertEquals('password', $parameters['selected_field']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'user_signin_error, user_signin_confirmation' => [
                'flashBagMessages' => [
                    'user_signin_error' => ['account-not-logged-in'],
                    'user_signin_confirmation' => ['user-activated'],
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['error_field']);
                            $this->assertEmpty($parameters['error_state']);
                            $this->assertEquals('account-not-logged-in', $parameters['user_signin_error']);
                            $this->assertEquals('user-activated', $parameters['user_signin_confirmation']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['stay_signed_in']);
                            $this->assertEquals('email', $parameters['selected_field']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    public function testRenderActionCachedResponse()
    {
        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var SignInController $signInController */
        $signInController = self::$container->get(SignInController::class);

        $response = $signInController->renderAction(
            self::$container->get(FlashBagValues::class),
            self::$container->get(CacheableResponseFactory::class),
            $request
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $signInController->renderAction(
            self::$container->get(FlashBagValues::class),
            self::$container->get(CacheableResponseFactory::class),
            $newRequest
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertCommonViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'email',
            'error_field',
            'error_state',
            'user_signin_error',
            'user_signin_confirmation',
            'redirect',
            'stay_signed_in',
            'selected_field',
        ];

        $keys = array_keys($parameters);
        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $keys);
        }
    }

    private function setTwigInViewRenderService(Twig_Environment $twig)
    {
        $viewRenderService = self::$container->get(ViewRenderService::class);

        $viewRenderServiceReflector = new ReflectionClass(ViewRenderService::class);
        $viewRenderServiceTwigProperty = $viewRenderServiceReflector->getProperty('twig');
        $viewRenderServiceTwigProperty->setAccessible(true);
        $viewRenderServiceTwigProperty->setValue($viewRenderService, $twig);
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
