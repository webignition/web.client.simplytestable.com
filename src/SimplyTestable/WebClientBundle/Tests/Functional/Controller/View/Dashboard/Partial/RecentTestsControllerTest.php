<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Dashboard\Partial;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
use SimplyTestable\WebClientBundle\Controller\View\Dashboard\Partial\RecentTestsController;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\InviteController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\TestList;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RecentTestsControllerTest extends BaseSimplyTestableTestCase
{
    const INDEX_ACTION_VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Dashboard/Partial/RecentTests:index.html.twig';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var RecentTestsController
     */
    private $recentTestsController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->recentTestsController = new RecentTestsController();
    }

//    public function testIndexActionGetRequest()
//    {
//        $this->setHttpFixtures([
//            HttpResponseFactory::createJsonResponse([
//                'team' => 'Team Name',
//                'user' => self::INVITE_USERNAME,
//                'token' => self::TOKEN,
//            ]),
//        ]);
//
//        $session = $this->container->get('session');
//        $flashBag = $session->getFlashBag();
//
//        $flashBag->set(ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY, 'foo');
//
//        $router = $this->container->get('router');
//        $requestUrl = $router->generate('view_user_signup_invite_index', [
//            'token' => self::TOKEN,
//        ]);
//
//        $this->client->request(
//            'GET',
//            $requestUrl
//        );
//
//        /* @var SymfonyResponse $response */
//        $response = $this->client->getResponse();
//
//        $this->assertTrue($response->isSuccessful());
//    }

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param EngineInterface $templatingEngine
     */
    public function testIndexAction(
        array $httpFixtures,
        EngineInterface $templatingEngine
    ) {
        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'simplytestable.services.testservice',
                'simplytestable.services.remotetestservice',
                'simplytestable.services.taskservice',
                'simplytestable.services.userservice',
            ],
            [
                'templating' => $templatingEngine,
            ]
        );

        $this->recentTestsController->setContainer($container);

        $response = $this->recentTestsController->indexAction();

        $this->assertTrue($response->isSuccessful());
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

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
                        'return' => new SymfonyResponse(),
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

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
                        'return' => new SymfonyResponse(),
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
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(self::INDEX_ACTION_VIEW_NAME, $viewName);
                            $this->assertNull($response);

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
                        'return' => new SymfonyResponse(),
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
}
