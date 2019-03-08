<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\History;

use App\Controller\View\Test\HistoryController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Model\RemoteTestList;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class HistoryControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-history.html.twig';
    const ROUTE_NAME = 'view_test_history_page1';
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

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'max_results' => 90,
                'limit' => HistoryController::TEST_LIST_LIMIT,
                'offset' => 90,
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
     * @dataProvider indexActionBadPageNumberDataProvider
     */
    public function testIndexActionBadPageNumber(
        array $httpFixtures,
        Request $request,
        string $expectedRedirectUrl,
        array $expectedRequestUrls
    ) {
        $userManager = self::$container->get(UserManager::class);

        $user = SystemUserService::getPublicUser();
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var HistoryController $historyController */
        $historyController = self::$container->get(HistoryController::class);

        $response = $historyController->indexAction($request);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEmpty($this->httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $this->httpHistory->getRequestUrlsAsStrings());
        }
    }

    public function indexActionBadPageNumberDataProvider(): array
    {
        return [
            'no page number' => [
                'httpFixtures' => [],
                'request' => new Request(),
                'expectedRedirectUrl' => '/history/',
                'expectedRequestUrls' => [],
            ],
            'zero page number' => [
                'httpFixtures' => [],
                'request' => new Request([], [], [
                    'page_number' => 0,
                ]),
                'expectedRedirectUrl' => '/history/',
                'expectedRequestUrls' => [],
            ],
            'negative page number' => [
                'httpFixtures' => [],
                'request' => new Request([], [], [
                    'page_number' => -1,
                ]),
                'expectedRedirectUrl' => '/history/',
                'expectedRequestUrls' => [],
            ],
            'greater than the number of pages: page number 10' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [
                            [
                                'id' => 1,
                                'task_types' => [],
                                'user' => 'user@example.com',
                                'state' => Test::STATE_IN_PROGRESS,
                                'type' => Test::TYPE_SINGLE_URL,
                                'task_count' => 10,
                                'website' => 'http://example.com/',
                            ],
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                        'task_type_options' => [],
                        'task_count' => 4,
                    ]),
                    HttpResponseFactory::createJsonResponse([1]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 1,
                            'url' => 'http://example.com',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ]),
                ],
                'request' => new Request([], [], [
                    'page_number' => 10,
                ]),
                'expectedRedirectUrl' => '/history/9/',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/90/?exclude-states%5B0%5D=rejected&exclude-current=1',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/ids/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'greater than the number of pages: page number 10; has filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'request' => new Request([
                    'filter' => 'foo',
                ], [], [
                    'page_number' => 10,
                ]),
                'expectedRedirectUrl' => '/history/9/?filter=foo',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/90/?exclude-states%5B0%5D=rejected&exclude-current=1&url-filter=foo',
                ],
            ],
            'greater than the number of pages: page number 20' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 190,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 10,
                        'jobs' => [],
                    ]),
                ],
                'request' => new Request([], [], [
                    'page_number' => 20,
                ]),
                'expectedRedirectUrl' => '/history/19/',
                'expectedRequestUrls' => [
                    'http://null/jobs/list/10/190/?exclude-states%5B0%5D=rejected&exclude-current=1',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionDataProvider
     */
    public function testIndexActionRender(array $httpFixtures, User $user, Request $request, Twig_Environment $twig)
    {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var HistoryController $historyController */
        $historyController = self::$container->get(HistoryController::class);
        $this->setTwigOnController($twig, $historyController);

        $response = $historyController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionDataProvider(): array
    {
        return [
            'page_number: 1' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 1,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(RemoteTestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                '/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 1; with filter' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([
                    'filter' => 'foo',
                ], [], [
                    'page_number' => 1,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                              $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(RemoteTestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEquals('foo', $parameters['filter']);
                            $this->assertEquals(
                                '/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 3' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 90,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 90,
                        'jobs' => [],
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 3,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(RemoteTestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    1, 2, 3, 4, 5, 6, 7, 8, 9,
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                '/history/websites/',
                                $parameters['websites_source']
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'page_number: 11' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 1000,
                        'limit' => HistoryController::TEST_LIST_LIMIT,
                        'offset' => 100,
                        'jobs' => [
                            [
                                'id' => 1,
                                'task_types' => [],
                                'user' => 'user@example.com',
                                'state' => Test::STATE_COMPLETED,
                            ],
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => 'user@example.com',
                        'state' => Test::STATE_COMPLETED,
                        'task_type_options' => [],
                        'task_count' => 4,
                    ]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request([], [], [
                    'page_number' => 11,
                ]),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertInstanceOf(RemoteTestList::class, $parameters['test_list']);
                            $this->assertEquals(
                                [
                                    11, 12, 13, 14, 15, 16, 17, 18, 19, 20
                                ],
                                $parameters['pagination_page_numbers']
                            );

                            $this->assertEmpty($parameters['filter']);
                            $this->assertEquals(
                                '/history/websites/',
                                $parameters['websites_source']
                            );

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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'max_results' => 0,
                'limit' => HistoryController::TEST_LIST_LIMIT,
                'offset' => 0,
                'jobs' => [],
            ]),
        ]);

        $request = new Request([], [], [
            'page_number' => 1,
        ]);

        self::$container->get('request_stack')->push($request);

        /* @var HistoryController $historyController */
        $historyController = self::$container->get(HistoryController::class);

        $response = $historyController->indexAction($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $historyController->indexAction($newRequest);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'test_list',
                'pagination_page_numbers',
                'filter',
                'websites_source',
            ],
            array_keys($parameters)
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
