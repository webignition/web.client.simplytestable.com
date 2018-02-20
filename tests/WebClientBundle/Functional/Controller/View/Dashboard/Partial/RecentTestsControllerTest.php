<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Dashboard\Partial;

use SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial\RecentTestsController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RecentTestsControllerTest extends AbstractBaseTestCase
{
    const INDEX_ACTION_VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Dashboard/Partial/RecentTests:index.html.twig';
    const VIEW_NAME = 'view_dashboard_partial_recenttests_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var RecentTestsController
     */
    private $recentTestsController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->recentTestsController = new RecentTestsController();
    }

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

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'max_results' => 0,
                'limit' => 100,
                'offset' => 0,
                'jobs' => [],
            ]),
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

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexAction(
        array $httpFixtures,
        EngineInterface $templatingEngine
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'SimplyTestable\WebClientBundle\Services\TestService',
                'SimplyTestable\WebClientBundle\Services\RemoteTestService',
                'SimplyTestable\WebClientBundle\Services\TaskService',
                'simplytestable.services.userservice',
            ],
            [
                'templating' => $templatingEngine,
            ]
        );

        $this->recentTestsController->setContainer($container);

        $response = $this->recentTestsController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        return [
            'no recent tests' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [],
                    ]),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            /* @var TestList $testList */
                            $testList = $parameters['test_list'];
                            $this->assertInstanceOf(TestList::class, $testList);

                            $this->assertTestList($testList, [
                                'maxResults' => 0,
                                'length' => 0,
                            ]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has recent tests' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 999,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => self::USER_EMAIL,
                                'state' => Test::STATE_COMPLETED,
                                'task_types' => [
                                    [
                                        'name' => Task::TYPE_HTML_VALIDATION,
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $testList = $parameters['test_list'];
                            $this->assertInstanceOf(TestList::class, $testList);

                            $this->assertTestList($testList, [
                                'maxResults' => 999,
                                'length' => 1,
                            ]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has recent tests; require results' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 999,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => self::USER_EMAIL,
                                'state' => Test::STATE_COMPLETED,
                                'type' => Test::TYPE_SINGLE_URL,
                                'task_types' => [
                                    [
                                        'name' => Task::TYPE_HTML_VALIDATION,
                                    ],
                                ],
                                'task_count' => 999,
                            ],
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        2,
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ]),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $testList = $parameters['test_list'];
                            $this->assertInstanceOf(TestList::class, $testList);

                            $this->assertTestList($testList, [
                                'maxResults' => 999,
                                'length' => 1,
                            ]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @param TestList $testList
     * @param array $expectedValues
     */
    private function assertTestList(TestList $testList, array $expectedValues)
    {
        $this->assertEquals(RecentTestsController::LIMIT, $testList->getLimit());
        $this->assertEquals(0, $testList->getOffset());
        $this->assertEquals($expectedValues['maxResults'], $testList->getMaxResults());
        $this->assertEquals($expectedValues['length'], $testList->getLength());

        $tests = $testList->get();

        foreach ($tests as $test) {
            $this->assertInstanceOf(RemoteTest::class, $test['remote_test']);
            $this->assertInstanceOf(Test::class, $test['test']);
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
