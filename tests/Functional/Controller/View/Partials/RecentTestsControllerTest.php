<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\RecentTestsController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\DecoratedTestList;
use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;
use App\Services\RemoteTestListService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Services\ObjectReflector;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class RecentTestsControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'Partials/Dashboard/recent-tests.html.twig';
    const ROUTE_NAME = 'view_partials_recenttests';
    const USER_EMAIL = 'user@example.com';

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'max_results' => 0,
                'limit' => 100,
                'offset' => 0,
                'jobs' => [],
            ]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionDataProvider
     */
    public function testIndexAction(
        RemoteTestList $remoteTestList,
        array $httpFixtures,
        Twig_Environment $twig
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);


        /* @var RecentTestsController $recentTestsController */
        $recentTestsController = self::$container->get(RecentTestsController::class);

        $remoteTestListService = $this->createRemoteTestListService($remoteTestList);

        $this->setRemoteTestListServiceOnController($recentTestsController, $remoteTestListService);
        $this->setTwigOnController($twig, $recentTestsController);

        $response = $recentTestsController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionDataProvider(): array
    {
        return [
            'no recent tests' => [
                'remoteTestList' => new RemoteTestList([], 0, 0, RecentTestsController::LIMIT),
                'httpFixtures' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            /* @var DecoratedTestList $decoratedTestList */
                            $decoratedTestList = $parameters['test_list'];
                            $this->assertInstanceOf(DecoratedTestList::class, $decoratedTestList);

                            $this->assertTestList($decoratedTestList, [
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
                'remoteTestList' => new RemoteTestList(
                    [
                        new RemoteTest([
                            'id' => 1,
                            'website' => 'http://example.com/',
                            'user' => self::USER_EMAIL,
                            'state' => Test::STATE_COMPLETED,
                            'task_types' => [
                                [
                                    'name' => Task::TYPE_HTML_VALIDATION,
                                ],
                            ],
                        ]),
                    ],
                    999,
                    0,
                    RecentTestsController::LIMIT
                ),
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [
                            [
                                'name' => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                        'user' => self::USER_EMAIL,
                        'state' => Test::STATE_COMPLETED,
                        'task_type_options' => [],
                        'task_count' => 4,
                    ]),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $decoratedTestList = $parameters['test_list'];
                            $this->assertInstanceOf(DecoratedTestList::class, $decoratedTestList);

                            $this->assertTestList($decoratedTestList, [
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
                'remoteTestList' => new RemoteTestList(
                    [
                        new RemoteTest([
                            'id' => 1,
                            'website' => 'http://example.com/',
                            'user' => self::USER_EMAIL,
                            'state' => Test::STATE_COMPLETED,
                            'type' => Test::TYPE_SINGLE_URL,
                            'task_types' => [
                                [
                                    'name' => Task::TYPE_HTML_VALIDATION,
                                ],
                            ],
                            'task_count' => 999,
                        ]),
                    ],
                    999,
                    0,
                    RecentTestsController::LIMIT
                ),
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [
                            [
                                'name' => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                        'user' => self::USER_EMAIL,
                        'state' => Test::STATE_COMPLETED,
                        'task_type_options' => [],
                        'task_count' => 4,
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
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $decoratedTestList = $parameters['test_list'];
                            $this->assertInstanceOf(DecoratedTestList::class, $decoratedTestList);

                            $this->assertTestList($decoratedTestList, [
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

    private function assertTestList(DecoratedTestList $testList, array $expectedValues)
    {
        $this->assertEquals(RecentTestsController::LIMIT, $testList->getLimit());
        $this->assertEquals(0, $testList->getOffset());
        $this->assertEquals($expectedValues['maxResults'], $testList->getMaxResults());
        $this->assertEquals($expectedValues['length'], $testList->getLength());
    }

    /**
     * @return RemoteTestListService|MockInterface
     */
    private function createRemoteTestListService(RemoteTestList $remoteTestList)
    {
        $remoteTestListService = \Mockery::mock(RemoteTestListService::class);
        $remoteTestListService
            ->shouldReceive('getRecent')
            ->with(RecentTestsController::LIMIT)
            ->andReturn($remoteTestList);

        return $remoteTestListService;
    }

    private function setRemoteTestListServiceOnController(
        RecentTestsController $recentTestsController,
        RemoteTestListService $remoteTestListService
    ) {
        ObjectReflector::setProperty(
            $recentTestsController,
            RecentTestsController::class,
            'remoteTestListService',
            $remoteTestListService
        );
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
