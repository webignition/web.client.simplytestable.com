<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\PreparingStatsController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Services\RemoteTestService;
use App\Services\TestService;
use App\Tests\Factory\HttpResponseFactory;
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
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
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
    public function testIndexActionRender(callable $testCreator, RemoteTest $remoteTest, array $expectedResponseData)
    {
        $test = $testCreator();

        /* @var PreparingStatsController $preparingStatsController */
        $preparingStatsController = self::$container->get(PreparingStatsController::class);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($preparingStatsController, $testService);
        $this->setRemoteTestServiceOnController($preparingStatsController, $remoteTestService);

        $response = $preparingStatsController->indexAction(self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponseData, $responseData);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no remote tasks' => [
                'testCreator' => function () {
                    return Test::create(self::TEST_ID);
                },
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'task_count' => 0,
                ])),
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 100,
                    'remaining_tasks_to_retrieve_count' => 0,
                    'local_task_count' => 0,
                    'remote_task_count' => 0,
                ],
            ],
            'no remote tasks retrieved' => [
                'testCreator' => function () {
                    return Test::create(self::TEST_ID);
                },
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 0,
                    'remaining_tasks_to_retrieve_count' => 12,
                    'local_task_count' => 0,
                    'remote_task_count' => 12,
                ],
            ],
            'some remote tasks retrieved' => [
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID);

                    $test->addTask(new Task());
                    $test->addTask(new Task());
                    $test->addTask(new Task());

                    return $test;
                },
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 25,
                    'remaining_tasks_to_retrieve_count' => 9,
                    'local_task_count' => 3,
                    'remote_task_count' => 12,
                ],
            ],
            'invalid remote test' => [
                'testCreator' => function () {
                    return null;
                },
                'remoteTest' => new RemoteTest([]),
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 0,
                    'remaining_tasks_to_retrieve_count' => 0,
                    'local_task_count' => 0,
                    'remote_task_count' => 0,
                ],
            ],
        ];
    }

    /**
     * @return TestService|MockInterface
     */
    private function createTestService(string $website, int $testId, ?Test $test)
    {
        $testService = \Mockery::mock(TestService::class);
        $testService
            ->shouldReceive('get')
            ->with($website, $testId)
            ->andReturn($test);

        return $testService;
    }

    /**
     * @return RemoteTestService|MockInterface
     */
    private function createRemoteTestService(int $testId, RemoteTest $remoteTest)
    {
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('get')
            ->with($testId)
            ->andReturn($remoteTest);

        return $remoteTestService;
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
