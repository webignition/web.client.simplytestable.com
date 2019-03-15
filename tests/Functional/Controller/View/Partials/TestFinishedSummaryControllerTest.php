<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\TestFinishedSummaryController;
use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Model\Test\DecoratedTest;
use App\Services\RemoteTestService;
use App\Services\TestService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;

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
        'state' => Test::STATE_FAILED_NO_SITEMAP,
        'task_type_options' => [],
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
        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));
        $remoteTest = new RemoteTest($this->remoteTestData);

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($testFinishedSummaryController, $testService);
        $this->setRemoteTestServiceOnController($testFinishedSummaryController, $remoteTestService);

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
        $test = Test::create(self::TEST_ID);
        $test->setWebsite(new NormalisedUrl(self::WEBSITE));

        $remoteTest = new RemoteTest($this->remoteTestData);
        $request = new Request();

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

        $testService = $this->createTestService(self::WEBSITE, self::TEST_ID, $test);
        $remoteTestService = $this->createRemoteTestService(self::TEST_ID, $remoteTest);

        $this->setTestServiceOnController($testFinishedSummaryController, $testService);
        $this->setRemoteTestServiceOnController($testFinishedSummaryController, $remoteTestService);

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
