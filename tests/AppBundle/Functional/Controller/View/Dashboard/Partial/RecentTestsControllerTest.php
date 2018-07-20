<?php

namespace Tests\AppBundle\Functional\Controller\View\Dashboard\Partial;

use AppBundle\Controller\View\Dashboard\Partial\RecentTestsController;
use AppBundle\Entity\Task\Task;
use AppBundle\Entity\Test\Test;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Model\RemoteTest\RemoteTest;
use AppBundle\Model\TestList;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;

class RecentTestsControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'Partials/Dashboard/recent-tests.html.twig';
    const ROUTE_NAME = 'view_dashboard_partial_recenttests_index';
    const USER_EMAIL = 'user@example.com';

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
     *
     * @param array $httpFixtures
     * @param Twig_Environment $twig
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexAction(
        array $httpFixtures,
        Twig_Environment $twig
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var RecentTestsController $recentTestsController */
        $recentTestsController = self::$container->get(RecentTestsController::class);
        $this->setTwigOnController($twig, $recentTestsController);

        $response = $recentTestsController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        return [
            'no recent tests' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 0,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [],
                    ]),
                ],
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

                            /* @var TestList $testList */
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 999,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => self::USER_EMAIL,
                                'state' => Test::STATE_COMPLETED,
                                'task_types' => [
                                    [
                                        'name' => Task::TYPE_HTML_VALIDATION,
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ],
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
            'has recent tests; require results' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'max_results' => 999,
                        'offset' => 0,
                        'limit' => RecentTestsController::LIMIT,
                        'jobs' => [
                            [
                                'id' => 1,
                                'user' => self::USER_EMAIL,
                                'state' => Test::STATE_COMPLETED,
                                'type' => Test::TYPE_SINGLE_URL,
                                'task_types' => [
                                    [
                                        'name' => Task::TYPE_HTML_VALIDATION,
                                    ],
                                ],
                                'task_count' => 999,
                            ],
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        2,
                    ]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'id' => 2,
                            'url' => 'http://example.com',
                            'state' => Task::STATE_COMPLETED,
                            'worker' => '',
                            'type' => Task::TYPE_HTML_VALIDATION,
                        ],
                    ]),
                ],
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

    /**
     * @param TestList $testList
     * @param array $expectedValues
     */
    private function assertTestList(TestList $testList, array $expectedValues)
    {
        $this->assertEquals(RecentTestsController::LIMIT, $testList->getLimit());
        $this->assertEquals(0, $testList->getOffset());
        $this->assertEquals($expectedValues['maxResults'], $testList->getMaxResults());
        $this->assertEquals($expectedValues['length'], $testList->getLength());

        $tests = $testList->get();

        foreach ($tests as $test) {
            $this->assertInstanceOf(RemoteTest::class, $test['remote_test']);
            $this->assertInstanceOf(Test::class, $test['test']);
        }
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