<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Test;

use App\Controller\View\Test\ProgressController;
use App\Entity\Task\Task;
use App\Entity\Test;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\TestModelFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ProgressControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'test-progress.html.twig';
    const ROUTE_NAME = 'view_test_progress';
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
        'state' => TestModel::STATE_IN_PROGRESS,
        'task_type_options' => [],
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_IN_PROGRESS,
        'type' => TestModel::TYPE_FULL_SITE,
        'taskTypes' => [
            Task::TYPE_HTML_VALIDATION,
        ],
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, string $expectedRedirectUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionInvalidGetRequestDataProvider(): array
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRedirectUrl' => '/signout/',
            ],
            'invalid owner, not logged in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedRedirectUrl' => sprintf(
                    '/signin/?redirect=%s%s',
                    'eyJyb3V0ZSI6InZpZXdfdGVzdF9wcm9ncmVzcyIsInBhcmFtZXRlcnMiOnsid2Vic2l0ZSI6Imh0dHA6XC9cL2V4',
                    'YW1wbGUuY29tXC8iLCJ0ZXN0X2lkIjoiMSJ9fQ%3D%3D'
                ),
            ],
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser(new User(self::USER_EMAIL));

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

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/http://example.com//1/unauthorised/', $response->getTargetUrl());
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
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionHttpRedirect(
        array $testModelProperties,
        User $user,
        string $website,
        int $testId,
        string $expectedRedirectUrl
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ProgressController $progressController */
        $progressController = self::$container->get(ProgressController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($progressController, $testRetriever);

        $response = $progressController->indexAction(new Request(), $website, $testId);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionJsRedirect(
        array $testModelProperties,
        User $user,
        string $website,
        int $testId,
        string $expectedRedirectUrl
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ProgressController $progressController */
        $progressController = self::$container->get(ProgressController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($progressController, $testRetriever);

        $request = new Request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');

        $response = $progressController->indexAction($request, $website, $testId);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent());

        $this->assertEquals('http://localhost' . $expectedRedirectUrl, $responseData->this_url);
    }

    public function indexActionRedirectDataProvider(): array
    {
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User('user@example.com');

        return [
            'remote test website not match request website' => [
                'testModelProperties' => [],
                'user' => $publicUser,
                'website' => 'foo',
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'finished test; state=completed' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_COMPLETED,
                ],
                'user' => $publicUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => '/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'finished test; state=failed_no_sitemap; public user' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_FAILED_NO_SITEMAP,
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                ],
                'user' => $publicUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => '/http://example.com//1/results/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
            'finished test; state=failed_no_sitemap; private user' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_FAILED_NO_SITEMAP,
                    'user' => $privateUser->getUsername(),
                ],
                'user' => $privateUser,
                'website' => self::WEBSITE,
                'testId' => self::TEST_ID,
                'expectedRedirectUrl' => '/1/re-test/',
                'expectedRequestUrl' => 'http://null/job/1/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderTextHtmlDataProvider
     */
    public function testIndexActionRenderTextHtml(
        array $testModelProperties,
        User $user,
        Twig_Environment $twig
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ProgressController $progressController */
        $progressController = self::$container->get(ProgressController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($progressController, $testRetriever);
        $this->setTwigOnController($twig, $progressController);

        $response = $progressController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderTextHtmlDataProvider(): array
    {
        return [
            'public user, in-progress, 79% done' => [
                'testModelProperties' => [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'remoteTaskCount' => 100,
                    'urlCount' => 50,
                    'taskCountByState' => [
                        'completed' => 79,
                    ],
                    'completionPercent' => 79,
                ],
                'user' => SystemUserService::getPublicUser(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertIsArray($parameters['website']);
                            $this->assertStateLabel($parameters, '50 urls, 100 tests; 79% done');
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);
                            $this->assertIsArray($parameters['test_options']);
                            $this->assertTrue($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'public user, queued, 0% done' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_QUEUED,
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'remoteTaskCount' => 44,
                    'urlCount' => 11,
                    'taskCountByState' => [],
                    'completionPercent' => 0,
                ],
                'user' => SystemUserService::getPublicUser(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertIsArray($parameters['website']);
                            $this->assertStateLabel(
                                $parameters,
                                '11 urls, 44 tests; waiting for first test to begin'
                            );
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                            ]);
                            $this->assertIsArray($parameters['test_options']);
                            $this->assertTrue($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, crawling' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_CRAWLING,
                    'user' => self::USER_EMAIL,
                    'remoteTaskCount' => 44,
                    'urlCount' => 11,
                    'taskCountByState' => [],
                    'completionPercent' => 0,
                    'crawlData' => [
                        'processed_url_count' => 10,
                        'discovered_url_count' => 30,
                        'limit' => 250,
                    ],
                ],
                'user' => new User(self::USER_EMAIL),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);
                            $this->assertTestAndRemoteTest($parameters);
                            $this->assertIsArray($parameters['website']);
                            $this->assertStateLabel(
                                $parameters,
                                'Finding URLs to test: 10 pages examined, 30 of 250 found'
                            );
                            $this->assertAvailableTaskTypes($parameters, [
                                'html-validation',
                                'css-validation',
                                'link-integrity',
                            ]);
                            $this->assertIsArray($parameters['test_options']);
                            $this->assertFalse($parameters['is_public_user_test']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderApplicationJsonDataProvider
     */
    public function testIndexActionRenderApplicationJson(
        array $testModelProperties,
        User $user,
        $expectedStateLabel
    ) {
        $testModel = TestModelFactory::create(array_merge($this->testModelProperties, $testModelProperties));

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        /* @var ProgressController $progressController */
        $progressController = self::$container->get(ProgressController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($progressController, $testRetriever);

        $request = new Request();
        $request->headers->set('accept', 'application/json');

        $response = $progressController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('application/json', $response->headers->get('content-type'));

        $responseData = json_decode($response->getContent(), true);

        $this->assertIsArray($responseData);
        $this->assertEquals([
            'test',
            'state_label',
            'this_url',
        ], array_keys($responseData));

        $this->assertIsArray($responseData['test']);

        $this->assertEquals('http://localhost/http://example.com//1/progress/', $responseData['this_url']);
        $this->assertEquals($expectedStateLabel, $responseData['state_label']);
    }

    public function indexActionRenderApplicationJsonDataProvider(): array
    {
        return [
            'public user, in-progress, 79% done' => [
                'testModelProperties' => [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'remoteTaskCount' => 100,
                    'urlCount' => 50,
                    'taskCountByState' => [
                        'completed' => 79,
                    ],
                    'completionPercent' => 79,
                ],
                'user' => SystemUserService::getPublicUser(),
                'expectedStateLabel' => '50 urls, 100 tests; 79% done',
            ],
            'public user, queued, 0% done' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_QUEUED,
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'remoteTaskCount' => 44,
                    'urlCount' => 11,
                    'taskCountByState' => [],
                    'completionPercent' => 0,
                ],
                'user' => SystemUserService::getPublicUser(),
                'expectedStateLabel' => '11 urls, 44 tests; waiting for first test to begin',
            ],
            'private user, crawling' => [
                'testModelProperties' => [
                    'state' => TestModel::STATE_CRAWLING,
                    'user' => self::USER_EMAIL,
                    'remoteTaskCount' => 44,
                    'urlCount' => 11,
                    'taskCountByState' => [],
                    'completionPercent' => 0,
                    'crawlData' => [
                        'processed_url_count' => 10,
                        'discovered_url_count' => 30,
                        'limit' => 250,
                    ],
                ],
                'user' => new User('user@example.com'),
                'expectedStateLabel' => 'Finding URLs to test: 10 pages examined, 30 of 250 found',
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $testModel = TestModelFactory::create($this->testModelProperties);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ProgressController $progressController */
        $progressController = self::$container->get(ProgressController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($progressController, $testRetriever);

        $response = $progressController->indexAction(
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
        $newResponse = $progressController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertEquals(
            [
                'user',
                'is_logged_in',
                'test',
                'state_label',
                'website',
                'available_task_types',
                'task_types',
                'test_options',
                'is_public_user_test',
                'css_validation_ignore_common_cdns',
                'default_css_validation_options',
            ],
            array_keys($parameters)
        );
    }

    private function assertTestAndRemoteTest(array $parameters)
    {
        /* @var DecoratedTest $test */
        $test = $parameters['test'];

        $this->assertInstanceOf(DecoratedTest::class, $test);

        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, $test->getWebsite());
    }

    private function assertStateLabel(array $parameters, string $expectedStateLabel)
    {
        $this->assertIsString($parameters['state_label']);
        $this->assertEquals($expectedStateLabel, $parameters['state_label']);
    }

    private function assertAvailableTaskTypes(array $parameters, array $expectedTaskTypeKeys)
    {
        $this->assertIsArray($parameters['available_task_types']);
        $this->assertEquals($expectedTaskTypeKeys, array_keys($parameters['available_task_types']));
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

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
