<?php

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\TestTaskListController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;

class TestTaskListControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'view_partials_test_task_list';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * @var array
     */
    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

    /**
     * @var array
     */
    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_IN_PROGRESS,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    /**
     * @var array
     */
    private $remoteTaskData = [
        'id' => 2,
        'url' => 'http://example.com/',
        'state' => Task::STATE_COMPLETED,
        'worker' => '',
        'type' => Task::TYPE_HTML_VALIDATION,
        'output' => [
            'output' => '',
            'content-type' => 'application/json',
            'error_count' => 0,
            'warning_count' => 0,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/signout/', $response->getTargetUrl());
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters),
            [
                'taskIds' => [2],
            ]
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * @dataProvider indexActionRenderEmptyContentDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRenderEmptyContent(
        array $httpFixtures,
        Request $request
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var TestTaskListController $testTaskListController */
        $testTaskListController = self::$container->get(TestTaskListController::class);

        $response = $testTaskListController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEmpty($response->getContent());
    }

    /**
     * @return array
     */
    public function indexActionRenderEmptyContentDataProvider()
    {
        return [
            'no request task ids' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'request' => new Request(),
            ],
            'invalid request task ids' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'request' => new Request([], [
                    'taskIds' => [
                        'foo', 'bar', true, false,
                    ],
                ]),
            ],
            'valid request task ids, no tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([]),
                ],
                'request' => new Request([], [
                    'taskIds' => [1, 2, 3],
                ]),
                'expectedResponseIsEmpty' => true,
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderContentDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param int $expectedPageIndex
     * @param array $expectedTaskSetCollection
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRenderContent(
        array $httpFixtures,
        Request $request,
        $expectedPageIndex,
        array $expectedTaskSetCollection
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var TestTaskListController $testTaskListController */
        $testTaskListController = self::$container->get(TestTaskListController::class);

        $response = $testTaskListController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $response);

        $content = $response->getContent();
        $this->assertNotEmpty($content);

        $crawler = new Crawler($content);

        $taskList = $crawler->filter('.task-list');
        $this->assertEquals($expectedPageIndex, $taskList->attr('data-page-index'));

        $taskSets = $crawler->filter('.task-set');

        $this->assertCount(count($expectedTaskSetCollection), $taskSets);

        $taskSets->each(function (Crawler $taskSet, $taskSetIndex) use ($expectedTaskSetCollection) {
            $expectedTaskSet = $expectedTaskSetCollection[$taskSetIndex];

            $taskSetUrl = $taskSet->filter('.url')->text();
            $this->assertEquals($expectedTaskSet['url'], $taskSetUrl);

            $tasks = $taskSet->filter('.task');

            $expectedTasks = $expectedTaskSet['tasks'];

            $tasks->each(function (Crawler $task, $taskIndex) use ($expectedTasks) {
                $expectedTask = $expectedTasks[$taskIndex];

                $this->assertEquals($expectedTask['id'], $task->attr('data-task-id'));
                $this->assertEquals($expectedTask['state'], $task->attr('data-state'));
                $this->assertEquals($expectedTask['type'], $task->filter('.type')->text());

                $errorLabel =  $task->filter('.label-danger');
                if (is_null($expectedTask['error_count'])) {
                    $this->assertEquals(0, $errorLabel->count());
                } else {
                    $trimmedErrorLabel = preg_replace('!\s+!', ' ', trim($errorLabel->text()));
                    $this->assertEquals($expectedTask['error_count'] . ' errors', $trimmedErrorLabel);
                }

                $warningLabel =  $task->filter('.label-primary');
                if (is_null($expectedTask['warning_count'])) {
                    $this->assertEquals(0, $warningLabel->count());
                } else {
                    $trimmedWarningLabel = preg_replace('!\s+!', ' ', trim($warningLabel->text()));
                    $this->assertEquals($expectedTask['warning_count'] . ' warnings', $trimmedWarningLabel);
                }
            });
        });
    }

    /**
     * @return array
     */
    public function indexActionRenderContentDataProvider()
    {
        return [
            'single task, no errors, no warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'id' => 2,
                            'url' => 'http://example.com/',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'state' => Task::STATE_COMPLETED,
                        ]),
                    ]),
                ],
                'request' => new Request([], [
                    'taskIds' => [2,],
                    'pageIndex' => 0,
                ]),
                'expectedPageIndex' => 0,
                'expectedTaskSetCollection' => [
                    [
                        'url' => 'http://example.com/',
                        'tasks' => [
                            [
                                'id' => 2,
                                'type' => Task::TYPE_HTML_VALIDATION,
                                'state' => Task::STATE_COMPLETED,
                                'error_count' => null,
                                'warning_count' => null,
                            ],
                        ],
                    ],
                ],
            ],
            'single task, has errors, has warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'id' => 2,
                            'url' => 'http://example.com/',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'state' => Task::STATE_COMPLETED,
                            'output' => [
                                'output' => '',
                                'content-type' => 'application/json',
                                'error_count' => 12,
                                'warning_count' => 22,
                            ],
                        ]),
                    ]),
                ],
                'request' => new Request([], [
                    'taskIds' => [2,],
                    'pageIndex' => 2,
                ]),
                'expectedPageIndex' => 2,
                'expectedTaskSetCollection' => [
                    [
                        'url' => 'http://example.com/',
                        'tasks' => [
                            [
                                'id' => 2,
                                'type' => Task::TYPE_HTML_VALIDATION,
                                'state' => Task::STATE_COMPLETED,
                                'error_count' => 12,
                                'warning_count' => 22,
                            ],
                        ],
                    ],
                ],
            ],
            'multiple tasks, no errors, no warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'id' => 2,
                            'url' => 'http://example.com/',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'state' => Task::STATE_COMPLETED,
                        ]),
                        array_merge($this->remoteTaskData, [
                            'id' => 3,
                            'url' => 'http://example.com/',
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'state' => Task::STATE_CANCELLED,
                        ]),
                        array_merge($this->remoteTaskData, [
                            'id' => 4,
                            'url' => 'http://example.com/foo',
                            'type' => Task::TYPE_HTML_VALIDATION,
                            'state' => Task::STATE_SKIPPED,
                        ]),
                        array_merge($this->remoteTaskData, [
                            'id' => 5,
                            'url' => 'http://example.com/foo',
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'state' => Task::STATE_IN_PROGRESS,
                        ]),
                    ]),
                ],
                'request' => new Request([], [
                    'taskIds' => [2, 3, 4, 5, ],
                    'pageIndex' => 3,
                ]),
                'expectedPageIndex' => 3,
                'expectedTaskSetCollection' => [
                    [
                        'url' => 'http://example.com/',
                        'tasks' => [
                            [
                                'id' => 2,
                                'type' => Task::TYPE_HTML_VALIDATION,
                                'state' => Task::STATE_COMPLETED,
                                'error_count' => null,
                                'warning_count' => null,
                            ],
                            [
                                'id' => 3,
                                'type' => Task::TYPE_CSS_VALIDATION,
                                'state' => Task::STATE_CANCELLED,
                                'error_count' => null,
                                'warning_count' => null,
                            ],
                        ],
                    ],
                    [
                        'url' => 'http://example.com/foo',
                        'tasks' => [
                            [
                                'id' => 4,
                                'type' => Task::TYPE_HTML_VALIDATION,
                                'state' => Task::STATE_SKIPPED,
                                'error_count' => null,
                                'warning_count' => null,
                            ],
                            [
                                'id' => 5,
                                'type' => Task::TYPE_CSS_VALIDATION,
                                'state' => Task::STATE_IN_PROGRESS,
                                'error_count' => null,
                                'warning_count' => null,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $request = new Request([], [
            'taskIds' => [2],
        ]);

        /* @var TestTaskListController $testTaskListController */
        $testTaskListController = self::$container->get(TestTaskListController::class);

        $response = $testTaskListController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $testTaskListController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }
}
