<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\IndexController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class IndexControllerTest extends AbstractViewControllerTest
{
    const INDEX_ACTION_VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Dashboard/Index:index.html.twig';
    const VIEW_NAME = 'view_dashboard_index_index';
    const USER_EMAIL = 'user@example.com';

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $user = new User(self::USER_EMAIL);
        $userSerializerService = $this->container->get(UserSerializerService::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializerService->serializeToString($user)
        ));

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param array $flashBagValues
     * @param Request $request
     * @param Twig_Environment $twig
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        array $flashBagValues,
        Request $request,
        Twig_Environment $twig
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

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
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'public user' => [
                'httpFixtures' => [],
                'user' => SystemUserService::getPublicUser(),
                'flashBagValues' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);

                            $this->assertEquals(null, $parameters['test_start_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user' => [
                'httpFixtures' => [],
                'user' => new User(self::USER_EMAIL),
                'flashBagValues' => [],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                                'js-static-analysis',
                                'link-integrity',
                            ]);

                            $this->assertEquals(null, $parameters['test_start_error']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user; has test start error' => [
                'httpFixtures' => [],
                'user' => new User(self::USER_EMAIL),
                'flashBagValues' => [
                    'test_start_error' => 'website-blank',
                ],
                'request' => new Request(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertAvailableTaskTypeKeys($parameters, [
                                'html-validation',
                                'css-validation',
                                'js-static-analysis',
                                'link-integrity',
                            ]);

                            $this->assertEquals('website-blank', $parameters['test_start_error']);

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
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'available_task_types',
                'task_types',
                'test_options',
                'css_validation_ignore_common_cdns',
                'js_static_analysis_ignore_common_cdns',
                'test_start_error',
                'website',
            ],
            array_keys($parameters)
        );
    }

    /**
     * @param array $parameters
     * @param array $expectedAvailableTaskTypeKeys
     */
    private function assertAvailableTaskTypeKeys(array $parameters, array $expectedAvailableTaskTypeKeys)
    {
        $availableTaskTypes = $parameters['available_task_types'];
        $this->assertEquals($expectedAvailableTaskTypeKeys, array_keys($availableTaskTypes));
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
