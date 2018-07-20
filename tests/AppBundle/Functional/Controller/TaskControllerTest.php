<?php

namespace Tests\AppBundle\Functional\Controller;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Controller\TaskController;
use AppBundle\Entity\Task\Task;
use AppBundle\Entity\Test\Test;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\Services\HttpMockHandler;

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

    /**
     * @var array
     */
    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [
            [
                'name' => Task::TYPE_HTML_VALIDATION,
            ],
        ],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    /**
     * @var array
     */
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
     *
     * @param string $method
     * @param string $routeName
     * @param array $routeParameters
     */
    public function testInvalidOwnerRequest($method, $routeName, array $routeParameters)
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

    /**
     * @return array
     */
    public function invalidOwnerRequestDataProvider()
    {
        return [
            'idCollectionAction' => [
                'method' => 'GET',
                'routeName' => 'app_task_ids',
                'routeParameters' => [
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID,
                ],
            ],
            'unretrievedIdCollectionAction' => [
                'method' => 'GET',
                'routeName' => 'app_unretrieved_task_ids',
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

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
        ]);

        /* @var JsonResponse $response */
        $response = $this->taskController->idCollectionAction(self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals($taskIds, $responseData);
    }

    /**
     * @dataProvider unretrievedIdCollectionActionRenderDataProvider
     *
     * @param int $limit
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testUnretrievedIdCollectionActionRender($limit)
    {
        $taskIds = [1, 2, 3, 4,];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse($taskIds),
        ]);

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

    /**
     * @return array
     */
    public function unretrievedIdCollectionActionRenderDataProvider()
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
     *
     * @param array $httpFixtures
     * @param Request $request
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testRetrieveActionRender(array $httpFixtures, Request $request)
    {
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $testRepository = $entityManager->getRepository(Test::class);

        /* @var Test $test */
        $test = $testRepository->findOneBy([
            'testId' => self::TEST_ID,
        ]);

        $this->assertNull($test);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var Response $response */
        $response = $this->taskController->retrieveAction($request, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

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

    /**
     * @return array
     */
    public function retrieveActionRenderDataProvider()
    {
        return [
            'no task ids' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([1, 2, 3, 4,]),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request(),
            ],
            'has task ids; comma-separated list' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request([], [
                    'remoteTaskIds' => '1,2,3,4',
                ]),
            ],
            'has task ids; range' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse($this->remoteTasksData),
                ],
                'request' => new Request([], [
                    'remoteTaskIds' => '1:4',
                ]),
            ],
        ];
    }
}