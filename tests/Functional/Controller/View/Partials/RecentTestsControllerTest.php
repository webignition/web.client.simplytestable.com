<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Partials;

use App\Controller\View\Partials\RecentTestsController;
use App\Entity\Test;
use App\Model\TestInterface;
use App\Model\TestList;
use App\Services\TestListRetriever;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Services\ObjectReflector;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class RecentTestsControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'Partials/Dashboard/recent-tests.html.twig';
    const ROUTE_NAME = 'view_partials_recenttests';
    const USER_EMAIL = 'user@example.com';

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionInvalidUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isRedirect('/signout/'));
    }

    public function testIndexActionGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse([
                'max_results' => 0,
                'limit' => 100,
                'offset' => 0,
                'jobs' => [],
            ]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionDataProvider
     */
    public function testIndexActionFoo(TestList $testList, array $httpFixtures, Twig_Environment $twig)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RecentTestsController $recentTestsController */
        $recentTestsController = self::$container->get(RecentTestsController::class);

        $testListRetriever = $this->createTestListRetriever($testList);

        $this->setTestListRetrieverOnController($recentTestsController, $testListRetriever);
        $this->setTwigOnController($twig, $recentTestsController);

        $response = $recentTestsController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    public function indexActionDataProvider(): array
    {
        return [
            'no recent tests' => [
                'testList' => new TestList([], 0, 0, RecentTestsController::LIMIT),
                'httpFixtures' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $testList = $parameters['test_list'];
                            $this->assertInstanceOf(TestList::class, $testList);

                            $this->assertTestList($testList, [
                                'maxResults' => 0,
                                'length' => 0,
                            ]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has recent tests' => [
                'testList' => new TestList(
                    [
                        \Mockery::mock(TestInterface::class),
                    ],
                    999,
                    0,
                    RecentTestsController::LIMIT
                ),
                'httpFixtures' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(self::VIEW_NAME, $viewName);

                            $this->assertEquals(
                                [
                                    'test_list',
                                ],
                                array_keys($parameters)
                            );

                            $testList = $parameters['test_list'];
                            $this->assertInstanceOf(TestList::class, $testList);

                            $this->assertTestList($testList, [
                                'maxResults' => 999,
                                'length' => 1,
                            ]);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    private function assertTestList(TestList $testList, array $expectedValues)
    {
        $this->assertEquals(RecentTestsController::LIMIT, ObjectReflector::getProperty($testList, 'limit'));
        $this->assertEquals(0, ObjectReflector::getProperty($testList, 'offset'));
        $this->assertEquals($expectedValues['maxResults'], $testList->getMaxResults());
        $this->assertEquals($expectedValues['length'], count($testList));
    }

    /**
     * @return TestListRetriever|MockInterface
     */
    private function createTestListRetriever(TestList $testList)
    {
        $testListRetriever = \Mockery::mock(TestListRetriever::class);
        $testListRetriever
            ->shouldReceive('getRecent')
            ->with(RecentTestsController::LIMIT)
            ->andReturn($testList);

        return $testListRetriever;
    }

    private function setTestListRetrieverOnController(
        RecentTestsController $recentTestsController,
        TestListRetriever $testListRetriever
    ) {
        ObjectReflector::setProperty(
            $recentTestsController,
            RecentTestsController::class,
            'testListRetriever',
            $testListRetriever
        );
    }

    private function createTestEntity(int $id)
    {
        $entity = Test::create($id + 1);
        ObjectReflector::setProperty(
            $entity,
            Test::class,
            'id',
            $id
        );

        return $entity;
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
