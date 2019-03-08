<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\RecentTestsController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;

use App\Model\RemoteTest\RemoteTest;
use App\Model\RemoteTestList;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
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
        array $httpFixtures,
        Twig_Environment $twig
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RecentTestsController $recentTestsController */
        $recentTestsController = self::$container->get(RecentTestsController::class);
        $this->setTwigOnController($twig, $recentTestsController);

        $response = $recentTestsController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionDataProvider(): array
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

                            /* @var RemoteTestList $remoteTestList */
                            $remoteTestList = $parameters['test_list'];
                            $this->assertInstanceOf(RemoteTestList::class, $remoteTestList);

                            $this->assertRemoteTestList($remoteTestList, [
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

                            $remoteTestList = $parameters['test_list'];
                            $this->assertInstanceOf(RemoteTestList::class, $remoteTestList);

                            $this->assertRemoteTestList($remoteTestList, [
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

                            $remoteTestList = $parameters['test_list'];
                            $this->assertInstanceOf(RemoteTestList::class, $remoteTestList);

                            $this->assertRemoteTestList($remoteTestList, [
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

    private function assertRemoteTestList(RemoteTestList $testList, array $expectedValues)
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
