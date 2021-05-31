<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results\Preparing;

use App\Controller\View\Test\Results\PreparingController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\TestModelFactory;
use App\Tests\Services\SymfonyRequestFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class PreparingControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-results-preparing.html.twig';
    const ROUTE_NAME = 'view_test_results_preparing';
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
        'task_count' => 4,
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

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isClientError());
        $this->assertEmpty($response->getContent());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([1, 2, 3,]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());

        $this->assertEquals(
            [
                'http://null/user/public/authenticate/',
                'http://null/job/1/is-authorised/',
                'http://null/job/1/',
                'http://null/job/1/tasks/ids/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    /**
     * @dataProvider indexActionNoRemoteTasksDataProvider
     */
    public function testIndexActionNoRemoteTasks(array $testModelProperties, string $expectedRedirectUrl)
    {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($preparingController, $testRetriever);

        $response = $preparingController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionNoRemoteTasksDataProvider(): array
    {
        return [
            'not finished' => [
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                    'state' => TestModel::STATE_IN_PROGRESS,
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'finished' => [
                'testModelProperties' => [
                    'remoteTaskCount' => 0,
                    'state' => TestModel::STATE_COMPLETED,
                ],
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     */
    public function testIndexActionBadRequest(
        array $testModelProperties,
        User $user,
        Request $request,
        string $website,
        string $expectedRedirectUrl
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($preparingController, $testRetriever);

        $response = $preparingController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionBadRequestDataProvider(): array
    {
        return [
            'website mismatch' => [
                'testModelProperties' => [
                    'website' => 'http://foo.example.com/',
                    'remoteTaskCount' => 100,
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://foo.example.com//1/results/preparing/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'incorrect state' => [
                'testModelProperties' => [
                    'remoteTaskCount' => 100,
                    'state' => TestModel::STATE_IN_PROGRESS,
                ],
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => self::WEBSITE,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
                'expectedRequestUrls' => 'http://null/job/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $testModelProperties,
        Twig_Environment $twig
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($preparingController, $testRetriever);
        $this->setTwigOnController($twig, $preparingController);

        $response = $preparingController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no remote tasks retrieved' => [
                'testModelProperties' => [
                    'entity' => $this->createTestEntity(self::TEST_ID, '1,2,3'),
                    'remoteTaskCount' => 4,
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(0, $parameters['completion_percent']);
                            $this->assertIsArray($parameters['website']);

                            /* @var TestModel $test */
                            $test = $parameters['test'];

                            $this->assertInstanceOf(TestModel::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, $test->getWebsite());

                            $this->assertEquals(0, $parameters['local_task_count']);
                            $this->assertEquals(4, $parameters['remote_task_count']);
                            $this->assertEquals(4, $parameters['remaining_tasks_to_retrieve_count']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'some remote tasks retrieved' => [
                'testModelProperties' => [
                    'entity' => $this->createTestEntity(self::TEST_ID, '1,2,3,4', 1),
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(25, $parameters['completion_percent']);
                            $this->assertIsArray($parameters['website']);

                            /* @var TestModel $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(TestModel::class, $test);
                            $this->assertEquals(self::TEST_ID, $test->getTestId());
                            $this->assertEquals(self::WEBSITE, $test->getWebsite());

                            $this->assertEquals(1, $parameters['local_task_count']);
                            $this->assertEquals(4, $parameters['remote_task_count']);
                            $this->assertEquals(3, $parameters['remaining_tasks_to_retrieve_count']);

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
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, [
            'remoteTaskCount' => 100,
        ]));

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($preparingController, $testRetriever);

        $response = $preparingController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

        $newResponse = $preparingController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'completion_percent',
                'website',
                'test',
                'local_task_count',
                'remote_task_count',
                'remaining_tasks_to_retrieve_count',
            ],
            array_keys($parameters)
        );
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

    private function createTestEntity(int $testId, string $taskIdCollection = '', ?int $taskCount = null): TestEntity
    {
        $testEntity = TestEntity::create($testId);
        $testEntity->setTaskIdCollection($taskIdCollection);

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
