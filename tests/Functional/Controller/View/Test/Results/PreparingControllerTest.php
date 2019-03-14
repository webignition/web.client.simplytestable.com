<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test\Results\Preparing;

use App\Controller\View\Test\Results\PreparingController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestService;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Doctrine\ORM\EntityManagerInterface;
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
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 4,
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
                'http://null/job/1/',
                'http://null/job/1/tasks/ids/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    /**
     * @dataProvider indexActionNoRemoteTasksDataProvider
     */
    public function testIndexActionNoRemoteTasks(
        RemoteTest $remoteTest,
        bool $testServiceIsFinished,
        string $expectedRedirectUrl
    ) {
        $test = Test::create(self::TEST_ID, self::WEBSITE);

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test, $testServiceIsFinished);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($preparingController, $testService);
        $this->setRemoteTestServiceOnController($preparingController, $remoteTestService);

        $response = $preparingController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionNoRemoteTasksDataProvider(): array
    {
        return [
            'not finished' => [
                'remoteTest' => new RemoteTest(array_merge(
                    $this->remoteTestData,
                    [
                        'task_count' => 0,
                        'state' => Test::STATE_IN_PROGRESS,
                    ]
                )),
                'testServiceIsFinished' => false,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'finished' => [
                'remoteTest' => new RemoteTest(array_merge(
                    $this->remoteTestData,
                    [
                        'task_count' => 0,
                        'state' => Test::STATE_COMPLETED,
                    ]
                )),
                'testServiceIsFinished' => true,
                'expectedRedirectUrl' => '/http://example.com//1/results/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionBadRequestDataProvider
     */
    public function testIndexActionBadRequest(
        Test $test,
        RemoteTest $remoteTest,
        bool $testServiceIsFinished,
        User $user,
        Request $request,
        string $website,
        string $expectedRedirectUrl
    ) {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testService = $this->createTestService($website, self::TEST_ID, $test, $testServiceIsFinished);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($preparingController, $testService);
        $this->setRemoteTestServiceOnController($preparingController, $remoteTestService);

        $response = $preparingController->indexAction($request, $website, self::TEST_ID);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionBadRequestDataProvider(): array
    {
        return [
            'website mismatch' => [
                'test' => Test::create(self::TEST_ID, self::WEBSITE),
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'website' => 'http://foo.example.com/',
                ])),
                'testServiceIsFinished' => true,
                'user' => SystemUserService::getPublicUser(),
                'request' => new Request(),
                'website' => 'http://foo.example.com/',
                'expectedRedirectUrl' => '/http://example.com//1/results/preparing/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'incorrect state' => [
                'test' => Test::create(self::TEST_ID, self::WEBSITE),
                'remoteTest' => new RemoteTest(array_merge($this->remoteTestData, [
                    'state' => Test::STATE_IN_PROGRESS,
                ])),
                'testServiceIsFinished' => false,
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
        callable $testCreator,
        RemoteTest $remoteTest,
        Twig_Environment $twig
    ) {
        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $test = $testCreator();
        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test, true);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($preparingController, $testService);
        $this->setRemoteTestServiceOnController($preparingController, $remoteTestService);
        $this->setTwigOnController($twig, $preparingController);

        $response = $preparingController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'no remote tasks retrieved' => [
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID, self::WEBSITE);
                    $test->setTaskIdCollection('1,2,3');

                    return $test;
                },
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(0, $parameters['completion_percent']);
                            $this->assertIsArray($parameters['website']);

                            /* @var Test $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(Test::class, $test);
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
                'testCreator' => function () {
                    $test = Test::create(self::TEST_ID, self::WEBSITE);
                    $test->setTaskIdCollection('1,2,3,4');
                    $test->setState(TestService::STATE_COMPLETED);

                    $task1 = new Task();
                    $task1->setState(Task::STATE_COMPLETED);
                    $task1->setTaskId(1);
                    $task1->setUrl('http://example.com/');
                    $task1->setType(Task::TYPE_HTML_VALIDATION);
                    $task1->setTest($test);

                    $test->addTask($task1);

                    $entityManager = self::$container->get(EntityManagerInterface::class);
                    $entityManager->persist($test);
                    $entityManager->flush();

                    return $test;
                },
                'remoteTest' => new RemoteTest($this->remoteTestData),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            $this->assertEquals(25, $parameters['completion_percent']);
                            $this->assertIsArray($parameters['website']);

                            /* @var Test $test */
                            $test = $parameters['test'];
                            $this->assertInstanceOf(Test::class, $test);
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
        $test = Test::create(self::TEST_ID, self::WEBSITE);
        $test->setTaskIdCollection('1,2,3');
        $remoteTest = new RemoteTest($this->remoteTestData);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var PreparingController $preparingController */
        $preparingController = self::$container->get(PreparingController::class);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test, true);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($preparingController, $testService);
        $this->setRemoteTestServiceOnController($preparingController, $remoteTestService);

        $response = $preparingController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
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
     * @return TestService|MockInterface
     */
    private function createTestService(string $website, int $testId, Test $test, bool $isFinished)
    {
        $testService = \Mockery::mock(TestService::class);
        $testService
            ->shouldReceive('get')
            ->with($website, $testId)
            ->andReturn($test);

        $testService
            ->shouldReceive('isFinished')
            ->with($test)
            ->andReturn($isFinished);

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
