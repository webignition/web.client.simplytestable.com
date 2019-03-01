<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results;

use App\Controller\View\Test\Results\PreparingStatsController;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
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
    public function testIndexActionRender(array $httpFixtures, array $testValues, array $expectedResponseData)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        /* @var PreparingStatsController $preparingStatsController */
        $preparingStatsController = self::$container->get(PreparingStatsController::class);

        $response = $preparingStatsController->indexAction(self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($expectedResponseData, $responseData);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no remote tasks' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'task_count' => 0,
                    ])),
                ],
                'testValues' => [],
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 100,
                    'remaining_tasks_to_retrieve_count' => 0,
                    'local_task_count' => 0,
                    'remote_task_count' => 0,
                ],
            ],
            'no remote tasks retrieved' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'testValues' => [],
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 0,
                    'remaining_tasks_to_retrieve_count' => 12,
                    'local_task_count' => 0,
                    'remote_task_count' => 12,
                ],
            ],
            'some remote tasks retrieved' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                ],
                'testValues' => [
                    TestFactory::KEY_TEST_ID => self::TEST_ID,
                    TestFactory::KEY_TASKS => [
                        [
                            TaskFactory::KEY_TASK_ID => 1,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_HTML_VALIDATION,
                        ],
                        [
                            TaskFactory::KEY_TASK_ID => 2,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_CSS_VALIDATION,
                        ],
                        [
                            TaskFactory::KEY_TASK_ID => 4,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_LINK_INTEGRITY,
                        ],
                    ],
                ],
                'expectedResponseData' => [
                    'id' => 1,
                    'completion_percent' => 25,
                    'remaining_tasks_to_retrieve_count' => 9,
                    'local_task_count' => 3,
                    'remote_task_count' => 12,
                ],
            ],
            'invalid remote test' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                ],
                'testValues' => [],
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
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
