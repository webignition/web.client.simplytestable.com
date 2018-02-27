<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User;

use SimplyTestable\WebClientBundle\Controller\View\User\SignInController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class SignInControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/SignIn:index.html.twig';
    const ROUTE_NAME = 'view_user_signin_index';

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

    public function testIndexActionIsLoggedIn()
    {
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        /* @var SignInController $signInController */
        $signInController = $this->container->get(SignInController::class);

        /* @var RedirectResponse $response */
        $response = $signInController->indexAction(new Request());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $flashBagValues
     * @param Request $request
     * @param Twig_Environment $twig
     */
    public function testIndexActionRender(
        array $flashBagValues,
        Request $request,
        Twig_Environment $twig
    ) {
        $session = $this->container->get('session');

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        /* @var SignInController $signInController */
        $signInController = $this->container->get(SignInController::class);
        $this->setTwigOnController($twig, $signInController);

        $response = $signInController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'no request data, no flash error messages' => [
                'flashBagValues' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEmpty($parameters['user_signin_error']);
                            $this->assertEmpty($parameters['user_signin_confirmation']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['stay_signed_in']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'request has email, stay-signed-in, redirect' => [
                'flashBagValues' => [],
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
                            $this->assertEmpty($parameters['user_signin_error']);
                            $this->assertEmpty($parameters['user_signin_confirmation']);
                            $this->assertEquals('foo', $parameters['redirect']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'user_signin_error, user_signin_confirmation' => [
                'flashBagValues' => [
                    'user_signin_error' => 'account-not-logged-in',
                    'user_signin_confirmation' => 'user-activated',
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['email']);
                            $this->assertEquals('account-not-logged-in', $parameters['user_signin_error']);
                            $this->assertEquals('user-activated', $parameters['user_signin_confirmation']);
                            $this->assertEmpty($parameters['redirect']);
                            $this->assertEmpty($parameters['stay_signed_in']);

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

        $this->container->get('request_stack')->push($request);

        /* @var SignInController $signInController */
        $signInController = $this->container->get(SignInController::class);

        $response = $signInController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $signInController->indexAction($newRequest);

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
                'user_signin_error',
                'user_signin_confirmation',
                'redirect',
                'stay_signed_in',
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
