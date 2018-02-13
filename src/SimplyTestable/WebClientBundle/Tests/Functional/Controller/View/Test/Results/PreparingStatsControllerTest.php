<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\Results\PreparingStatsController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PreparingStatsControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'view_test_results_preparingstats_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var PreparingStatsController
     */
    private $preparingStatsController;

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

        $this->preparingStatsController = new PreparingStatsController();
        $this->preparingStatsController->setContainer($this->container);
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('http://localhost/signout/'));
    }

    public function testIndexActionInvalidOwnerGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
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
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $response = $this->preparingStatsController->indexAction(self::WEBSITE, self::TEST_ID);
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
