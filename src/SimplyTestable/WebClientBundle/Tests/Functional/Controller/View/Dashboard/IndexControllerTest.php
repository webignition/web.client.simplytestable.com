<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\IndexController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends AbstractBaseTestCase
{
    const INDEX_ACTION_VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Dashboard/Index:index.html.twig';
    const VIEW_NAME = 'view_dashboard_index_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(404),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
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
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::VIEW_NAME);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
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
     * @param EngineInterface $templatingEngine
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        array $flashBagValues,
        Request $request,
        EngineInterface $templatingEngine
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'simplytestable.services.tasktypeservice',
                'simplytestable.services.userservice',
                'simplytestable.services.testoptions.adapter.factory',
                'simplytestable.services.userserializerservice',
                'simplytestable.services.urlviewvalues',
                'simplytestable.services.cachevalidator',
                'simplytestable.services.flashbagvalues',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
                'test_options',
                'css-validation-ignore-common-cdns',
                'js-static-analysis-ignore-common-cdns',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction($request);
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
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'flashBagValues' => [],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
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

        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $this->indexController->indexAction($newRequest);

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
                'public_site',
                'external_links',
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
