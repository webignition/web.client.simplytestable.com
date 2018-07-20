<?php

namespace Tests\AppBundle\Functional\Controller\View\Test\Results;

use AppBundle\Controller\View\Test\Results\PreparingStatsController;
use AppBundle\Entity\Task\Task;
use AppBundle\Entity\Test\Test;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidCredentialsException;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Factory\TaskFactory;
use Tests\AppBundle\Factory\TestFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Tests\AppBundle\Functional\Controller\AbstractControllerTest;
use Tests\AppBundle\Services\HttpMockHandler;

class PreparingStatsControllerTest extends AbstractControllerTest
{
    const ROUTE_NAME = 'view_test_results_preparingstats_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var array
     */
    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
    ];

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
        'task_types' => [],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
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
        $this->assertEquals(
            [
                'id' => 0,
                'completion_percent' => 0,
                'remaining_tasks_to_retrieve_count' => 0,
                'local_task_count' => 0,
                'remote_task_count' => 0,
            ],
            $responseData
        );
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
     *
     * @param array $httpFixtures
     * @param array $testValues
     * @param array $expectedResponseData
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
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

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
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
                            TaskFactory::KEY_TASK_ID => 3,
                            TaskFactory::KEY_URL => 'http://example.com/',
                            TaskFactory::KEY_TYPE => Task::TYPE_JS_STATIC_ANALYSIS,
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
                    'completion_percent' => 33,
                    'remaining_tasks_to_retrieve_count' => 8,
                    'local_task_count' => 4,
                    'remote_task_count' => 12,
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
