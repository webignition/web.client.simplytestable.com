<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller;

use App\Services\TestService;
use App\Tests\Services\ObjectReflector;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\TaskController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Tests\Factory\HttpResponseFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Services\HttpMockHandler;

class TaskControllerTest extends AbstractControllerTest
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var TaskController
     */
    private $taskController;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    private $remoteTasksData = [
        [
            'id' => 1,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_HTML_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 2,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_CSS_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 0,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 3,
            'url' => 'http://example.com/foo',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_HTML_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
        [
            'id' => 4,
            'url' => 'http://example.com/',
            'state' => Task::STATE_COMPLETED,
            'worker' => '',
            'type' => Task::TYPE_CSS_VALIDATION,
            'output' => [
                'output' => '',
                'content-type' => 'application/json',
                'error_count' => 1,
                'warning_count' => 0,
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->taskController = self::$container->get(TaskController::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    /**
     * @dataProvider invalidOwnerRequestDataProvider
     */
    public function testInvalidOwnerRequest(string $method, string $routeName, array $routeParameters)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->request(
            $method,
            $this->router->generate($routeName, $routeParameters)
        );

        /* @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals([], $responseData);
    }

    public function invalidOwnerRequestDataProvider(): array
    {
        return [
            'idCollectionAction' => [
                'method' => 'GET',
                'routeName' => 'test_task_ids',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unretrievedIdCollectionAction' => [
                'method' => 'GET',
                'routeName' => 'test_task_ids_unretrieved',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                    'limit' => TaskController::DEFAULT_UNRETRIEVED_TASKID_LIMIT,
                ],
            ],
            'retrieveAction' => [
                'method' => 'POST',
                'routeName' => 'task_results_retrieve',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
        ];
    }

    public function testIdCollectionActionRender()
    {
        $taskIds = [1, 2, 3, 4,];

        $test = Test::create(self::TEST_ID);
        $test->setTaskIdCollection(implode(',', $taskIds));

        $testService = $this->createTestService(self::TEST_ID, $test);
        $this->setTestServiceOnController($this->taskController, $testService);

        /* @var JsonResponse $response */
        $response = $this->taskController->idCollectionAction(self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($taskIds, $responseData);
    }

    /**
     * @dataProvider unretrievedIdCollectionActionRenderDataProvider
     */
    public function testUnretrievedIdCollectionActionRender(?int $limit)
    {
        $taskIds = [1, 2, 3, 4,];

        $test = Test::create(self::TEST_ID);
        $test->setTaskIdCollection(implode(',', $taskIds));

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($test);
        $entityManager->flush();

        $testService = $this->createTestService(self::TEST_ID, $test);
        $this->setTestServiceOnController($this->taskController, $testService);

        /* @var JsonResponse $response */
        $response = $this->taskController->unretrievedIdCollectionAction(
            self::WEBSITE,
            self::TEST_ID,
            $limit
        );

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($taskIds, $responseData);
    }

    public function unretrievedIdCollectionActionRenderDataProvider(): array
    {
        return [
            'no specified limit' => [
                'limit' => null,
            ],
            'limit exceeds maximum' => [
                'limit' => TaskController::MAX_UNRETRIEVED_TASKID_LIMIT + 1,
            ],
        ];
    }

    /**
     * @dataProvider retrieveActionRenderDataProvider
     */
    public function testRetrieveActionRender(array $existingTestTaskIds, array $httpFixtures, Request $request)
    {
        $test = Test::create(self::TEST_ID);
        $test->setTaskIdCollection(implode(',', $existingTestTaskIds));

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($test);
        $entityManager->flush();

        $testService = $this->createTestService(self::TEST_ID, $test);
        $this->setTestServiceOnController($this->taskController, $testService);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var Response $response */
        $response = $this->taskController->retrieveAction($request, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $testRepository = $entityManager->getRepository(Test::class);

        /* @var Test $test */
        $test = $testRepository->findOneBy([
            'testId' => self::TEST_ID,
        ]);

        $this->assertInstanceOf(Test::class, $test);

        $tasks = $test->getTasks();
        $this->assertCount(4, $tasks);

        foreach ($tasks as $task) {
            $this->assertInstanceOf(Task::class, $task);
        }
    }

    public function retrieveActionRenderDataProvider(): array
    {
        return [
            'no task ids requested' => [
                'existingTestTaskIds' => [1, 2, 3, 4,],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request(),
            ],
            'has task ids; comma-separated list' => [
                'existingTestTaskIds' => [],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request([], [
                    'remoteTaskIds' => '1,2,3,4',
                ]),
            ],
            'has task ids; range' => [
                'existingTestTaskIds' => [],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request([], [
                    'remoteTaskIds' => '1:4',
                ]),
            ],
        ];
    }

    /**
     * @return TestService|MockInterface
     */
    private function createTestService(int $testId, ?Test $test)
    {
        $testService = \Mockery::mock(TestService::class);
        $testService
            ->shouldReceive('get')
            ->with($testId)
            ->andReturn($test);

        return $testService;
    }

    protected function setTestServiceOnController($controller, TestService $testService)
    {
        ObjectReflector::setProperty(
            $controller,
            get_class($controller),
            'testService',
            $testService
        );
    }
}
