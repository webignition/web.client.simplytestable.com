<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\PreparingStatsController;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Services\TestRetriever;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestModelFactory;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PreparingStatsControllerTest extends AbstractViewControllerTest
{
    const ROUTE_NAME = 'view_test_results_preparing_stats';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'type' => TestModel::TYPE_FULL_SITE,
        'taskTypes' => [],
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
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

        $this->assertTrue($response->isRedirect('/signout/'));
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

        /* @var JsonResponse $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals([], $responseData);
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(array $testModelProperties, array $expectedResponseData)
    {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        /* @var PreparingStatsController $preparingStatsController */
        $preparingStatsController = self::$container->get(PreparingStatsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($preparingStatsController, $testRetriever);

        $response = $preparingStatsController->indexAction(self::TEST_ID);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponseData, $responseData);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no remote tasks' => [
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                ],
                'expectedResponseData' => [
                    'id' => self::TEST_ID,
                    'completion_percent' => 100,
                    'remaining_tasks_to_retrieve_count' => 0,
                    'local_task_count' => 0,
                    'remote_task_count' => 0,
                ],
            ],
            'no remote tasks retrieved' => [
                'testModelProperties' => [
                    'remoteTaskCount' => 12,
                ],
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 0,
                    'remaining_tasks_to_retrieve_count' => 12,
                    'local_task_count' => 0,
                    'remote_task_count' => 12,
                ],
            ],
            'some remote tasks retrieved' => [
                'testModelProperties' => [
                    'entity' => $this->createTestEntity(self::TEST_ID, 3),
                    'remoteTaskCount' => 12,
                ],
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 25,
                    'remaining_tasks_to_retrieve_count' => 9,
                    'local_task_count' => 3,
                    'remote_task_count' => 12,
                ],
            ],
        ];
    }

    /**
     * @return TestRetriever|MockInterface
     */
    private function createTestRetriever(int $testId, ?TestModel $testModel)
    {
        $testRetriever = \Mockery::mock(TestRetriever::class);
        $testRetriever
            ->shouldReceive('retrieve')
            ->with($testId)
            ->andReturn($testModel);

        return $testRetriever;
    }

    private function createTestEntity(int $testId, ?int $taskCount = null): TestEntity
    {
        $testEntity = TestEntity::create($testId);

        if ($taskCount) {
            for ($taskIndex = 0; $taskIndex < $taskCount; $taskIndex++) {
                $testEntity->addTask(new Task());
            }
        }

        return $testEntity;
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
