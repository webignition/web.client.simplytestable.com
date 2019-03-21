<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\TestFinishedSummaryController;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Model\Test\DecoratedTest;
use App\Services\TestRetriever;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\TestModelFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class TestFinishedSummaryControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'Partials/Test/Summary/finished.html.twig';
    const ROUTE_NAME = 'view_partials_test_finished_summary';
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
        'state' => TestModel::STATE_FAILED_NO_SITEMAP,
        'task_type_options' => [],
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'type' => TestEntity::TYPE_FULL_SITE,
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
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(Twig_Environment $twig)
    {
        $testModel = TestModelFactory::create($this->testModelProperties);

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($testFinishedSummaryController, $testRetriever);
        $this->setTwigOnController($twig, $testFinishedSummaryController);

        $response = $testFinishedSummaryController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        return [
            'default' => [
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);
                            $this->assertViewParameterKeys($parameters);

                            /* @var DecoratedTest $decoratedTest */
                            $decoratedTest = $parameters['test'];
                            $this->assertInstanceOf(DecoratedTest::class, $decoratedTest);
                            $this->assertEquals(self::TEST_ID, $decoratedTest->getTestId());
                            $this->assertEquals(self::WEBSITE, $decoratedTest->getWebsite());

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
        $testModel = TestModelFactory::create($this->testModelProperties);

        $request = new Request();

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($testFinishedSummaryController, $testRetriever);

        $response = $testFinishedSummaryController->indexAction($request, self::WEBSITE, self::TEST_ID);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $testFinishedSummaryController->indexAction($newRequest, self::WEBSITE, self::TEST_ID);

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

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
