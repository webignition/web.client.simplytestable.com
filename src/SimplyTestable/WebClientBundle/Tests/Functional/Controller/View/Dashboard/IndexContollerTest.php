<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Dashboard;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\IndexController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexContollerTest extends BaseSimplyTestableTestCase
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
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param array $flashBagValues
     * @param Request $request
     * @param EngineInterface $templatingEngine
     */
    public function testIndexAction(
        array $httpFixtures,
        User $user,
        array $flashBagValues,
        Request $request,
        EngineInterface $templatingEngine
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $session = $this->container->get('session');

        $userService->setUser($user);

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
                'session',
                'simplytestable.services.cacheableresponseservice',
                'simplytestable.services.userserializerservice',
                'simplytestable.services.urlviewvalues',
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
    public function indexActionDataProvider()
    {
        return [
            'public user' => [
                'httpFixtures' => [],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'flashBagValues' => [],
                'request' => new Request(),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);
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
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
