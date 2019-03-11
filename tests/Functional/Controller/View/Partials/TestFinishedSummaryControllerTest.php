<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\TestFinishedSummaryController;
use App\Entity\Test\Test;
use App\Model\Test\DecoratedTest;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
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

    public function testIndexActionInvalidRemoteTest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

        $response = $testFinishedSummaryController->indexAction(new Request(), self::WEBSITE, self::TEST_ID);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEmpty($response->getContent());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(Twig_Environment $twig)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
        ]);

        $request = new Request();

        /* @var TestFinishedSummaryController $testFinishedSummaryController */
        $testFinishedSummaryController = self::$container->get(TestFinishedSummaryController::class);

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
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
