<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\Task;

use App\Controller\View\Task\ResultsController;
use App\Entity\Task\Task;
use App\Entity\Test as TestEntity;
use App\Model\Test as TestModel;
use App\Model\DecoratedTest;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Factory\TestModelFactory;
use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ResultsControllerTest extends AbstractViewControllerTest
{
    const VIEW_NAME = 'task-results.html.twig';
    const ROUTE_NAME = 'view_task_results';
    const ROUTE_NAME_VERBOSE = 'view_task_results_verbose';
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const TASK_ID = 2;
    const USER_EMAIL = 'user@example.com';

    private $routeParameters = [
        'website' => self::WEBSITE,
        'test_id' => self::TEST_ID,
        'task_id' => self::TASK_ID,
    ];

    private $remoteTestData = [
        'id' => self::TEST_ID,
        'website' => self::WEBSITE,
        'task_types' => [
            [
                'name' => Task::TYPE_HTML_VALIDATION,
            ],
            [
                'name' => Task::TYPE_CSS_VALIDATION,
            ],
            [
                'name' => Task::TYPE_LINK_INTEGRITY,
            ],
        ],
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    private $testModelProperties = [
        'website' => self::WEBSITE,
        'user' => self::USER_EMAIL,
        'state' => TestModel::STATE_COMPLETED,
        'type' => TestModel::TYPE_FULL_SITE,
        'taskTypes' => [
            Task::TYPE_HTML_VALIDATION,
            Task::TYPE_CSS_VALIDATION,
            Task::TYPE_LINK_INTEGRITY,
        ],
    ];

    private $remoteTaskData = [
        'id' => 2,
        'url' => 'http://example.com/',
        'state' => Task::STATE_COMPLETED,
        'worker' => '',
        'type' => Task::TYPE_HTML_VALIDATION,
        'output' => [
            'output' => '',
            'content-type' => 'application/json',
            'error_count' => 1,
            'warning_count' => 0,
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
                    'eyJyb3V0ZSI6InZpZXdfdGVzdF9wcm9ncmVzcyIsInBhcmFtZXRlcnMiOnsid2Vic2l0ZSI6Imh0dHA6XC9cL2V4Y',
                    'W1wbGUuY29tXC8iLCJ0ZXN0X2lkIjoiMSJ9fQ%3D%3D'
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

    public function testIndexActionVerboseRoutePublicUserGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME_VERBOSE, $this->routeParameters)
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionPublicUserGetRequestDataProvider
     */
    public function testIndexActionPublicUserGetRequest(bool $includeAdditionalTrailingSlash)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
            . ($includeAdditionalTrailingSlash ? '/' : '')
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
                'http://null/job/1/tasks/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    public function indexActionPublicUserGetRequestDataProvider(): array
    {
        return [
            'without additional trailing slash' => [
                'includeAdditionalTrailingSlash' => false,
            ],
            'with additional trailing slash' => [
                'includeAdditionalTrailingSlash' => true,
            ],
        ];
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     */
    public function testIndexActionRedirect(array $httpFixtures, string $expectedRedirectUrl)
    {
        $testModel = $this->createTest([
            'state' => TestModel::STATE_IN_PROGRESS,
        ]);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);
        $this->setTestRetrieverOnController($resultsController, $testRetriever);

        /* @var RedirectResponse $response */
        $response = $resultsController->indexAction(
            new Request(),
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    public function indexActionRedirectDataProvider(): array
    {
        return [
            'invalid task' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'id' => self::TASK_ID + 1,
                        ]),
                    ]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'task has no errors and no warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'output' => [
                                'output' => '',
                                'content-type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 0,
                            ]
                        ]),
                    ]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
            'incomplete task' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'state' => Task::STATE_IN_PROGRESS,
                        ]),
                    ]),
                ],
                'expectedRedirectUrl' => '/http://example.com//1/progress/',
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $testModelProperties,
        array $httpFixtures,
        User $user,
        Twig_Environment $twig
    ) {
        $testModel = $this->createTest($testModelProperties);

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        $this->httpMockHandler->appendFixtures($httpFixtures);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($resultsController, $testRetriever);
        $this->setTwigOnController($twig, $resultsController);

        $response = $resultsController->indexAction(
            new Request(),
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function indexActionRenderDataProvider(): array
    {
        $privateUser = new User(self::USER_EMAIL);

        return [
            'public user, public test' => [
                'testModelProperties' => [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => SystemUserService::getPublicUser(),
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_owner' => true,
                                    'is_public_user_test' => true,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, public test' => [
                'testModelProperties' => [
                    'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    'owners' => [
                        SystemUserService::PUBLIC_USER_USERNAME,
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_owner' => false,
                                    'is_public_user_test' => true,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'private user, private test' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertParameterData(
                                [
                                    'is_owner' => true,
                                    'is_public_user_test' => false,
                                ],
                                $parameters
                            );

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'html validation; view keys' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertArrayHasKey('documentation_urls', $parameters);
                            $this->assertArrayHasKey('fixes', $parameters);
                            $this->assertArrayHasKey('distinct_fixes', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'html validation; error documentation urls; warnings, no errors' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'output' => [
                                'output' => '',
                                'content-type' => 'application/json',
                                'error_count' => 0,
                                'warning_count' => 1,
                            ]
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertEquals([], $parameters['documentation_urls']);
                            $this->assertEquals([], $parameters['fixes']);
                            $this->assertEquals([], $parameters['distinct_fixes']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'html validation; error documentation urls; has errors' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'output' => [
                                'output' => json_encode([
                                    'messages' => [
                                        [
                                            'lastLine' => 1,
                                            'lastColumn' => 1,
                                            'message' =>
                                                'An img element must have an alt attribute, except under certain'
                                                .' conditions. For details, consult guidance on providing text '
                                                .'alternatives for images.',
                                            'messageId' => 'html5',
                                            'type' => 'error',
                                        ],
                                        [
                                            'lastLine' => 1,
                                            'lastColumn' => 1,
                                            'message' =>
                                                '& did not start a character reference. (& probably should have been '
                                                .'escaped as &amp;.)',
                                            'messageId' => 'html5',
                                            'type' => 'error',
                                        ],
                                        [
                                            'lastLine' => 1,
                                            'lastColumn' => 1,
                                            'message' => 'required attribute "alt" not specified',
                                            'messageId' => '127',
                                            'type' => 'error',
                                        ],
                                        [
                                            'lastLine' => 1,
                                            'lastColumn' => 1,
                                            'message' => 'Forbidden code point U+001b.',
                                            'messageId' => 'html5',
                                            'type' => 'error',
                                        ],
                                    ],
                                ]),
                                'content-type' => 'application/json',
                                'error_count' => 1,
                                'warning_count' => 0,
                            ]
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $expectedError1Url = 'https://help.simplytestable.com/errors/html-validation/an-img-'
                                .'element-must-have-an-alt-attribute-except-under-certain-conditions-for-details-'
                                .'consult-guidance-on-providing-text-alternatives-for-images/';

                            $expectedError2Url = 'https://help.simplytestable.com/errors/html-validation/ampersand-did-'
                                .'not-start-a-character-reference-ampersand-probably-should-have-been-'
                                .'escaped-as-amp/';

                            $expectedError3Url = 'https://help.simplytestable.com/errors/html-validation/required-'
                                .'attribute-x-not-specified/required-attribute-alt-not-specified/';

                            $expectedError4Url = 'https://help.simplytestable.com/errors/html-validation/forbidden-'
                                .'code-point-x/';

                            $expectedFixes = [
                                [
                                    'error' => 'An img element must have an alt attribute, except under certain'
                                        .' conditions. For details, consult guidance on providing text '
                                        .'alternatives for images.',
                                    'documentation_url' => $expectedError1Url,
                                ],
                                [
                                    'error' => '& did not start a character reference. (& probably should have been '
                                        .'escaped as &amp;.)',
                                    'documentation_url' => $expectedError2Url,
                                ],
                                [
                                    'error' => 'Required attribute "alt" not specified',
                                    'documentation_url' => $expectedError3Url,
                                ]
                            ];

                            $this->assertEquals([
                                [
                                    'url' => $expectedError1Url,
                                    'exists' => true,
                                ],
                                [
                                    'url' => $expectedError2Url,
                                    'exists' => true,
                                ],
                                [
                                    'url' => $expectedError3Url,
                                    'exists' => true,
                                ],
                                [
                                    'url' => $expectedError4Url,
                                    'exists' => false,
                                ],
                            ], $parameters['documentation_urls']);

                            $this->assertEquals($expectedFixes, $parameters['fixes']);
                            $this->assertEquals($expectedFixes, $parameters['distinct_fixes']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'css validation; view keys' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_CSS_VALIDATION,
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertArrayHasKey('errors_by_ref', $parameters);
                            $this->assertArrayHasKey('warnings_by_ref', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'css validation; has errors' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_CSS_VALIDATION,
                            'output' => [
                                'output' => json_encode([
                                    [
                                        'message' => 'Property colour doesn&#39;t exist :\n\t\t#bbbbbb',
                                        'context' => '',
                                        'line_number' => 1,
                                        'ref' => 'http://example.com/foo.css',
                                        'type' => 'error',
                                    ],
                                    [
                                        'message' => 'Unknown pseudo-element or pseudo-class :visted',
                                        'context' => '',
                                        'line_number' => 1,
                                        'ref' => 'http://example.com/foo.css',
                                        'type' => 'error',
                                    ],
                                    [
                                        'message' => 'Property -moz-border-radius is an unknown vendor extension',
                                        'context' => '',
                                        'line_number' => 1,
                                        'ref' => 'http://example.com/foo.css',
                                        'type' => 'warning',
                                    ],
                                    [
                                        'message' => 'Property -moz-border-radius is an unknown vendor extension',
                                        'context' => '',
                                        'line_number' => 1,
                                        'ref' => '',
                                        'type' => 'warning',
                                    ],
                                ]),
                                'content-type' => 'application/json',
                                'error_count' => 2,
                                'warning_count' => 1,
                            ]
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $errorsByRef = $parameters['errors_by_ref'];

                            $this->assertEquals(['http://example.com/foo.css'], array_keys($errorsByRef));
                            $this->assertCount(2, $errorsByRef['http://example.com/foo.css']);

                            $warningsByRef = $parameters['warnings_by_ref'];

                            $this->assertEquals(['http://example.com/foo.css', ''], array_keys($warningsByRef));
                            $this->assertCount(1, $warningsByRef['http://example.com/foo.css']);
                            $this->assertCount(1, $warningsByRef['']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'link integrity; view keys' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_LINK_INTEGRITY,
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $this->assertArrayHasKey('errors_by_link_state', $parameters);
                            $this->assertArrayHasKey('link_class_labels', $parameters);
                            $this->assertArrayHasKey('link_state_descriptions', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'link integrity; has errors' => [
                'testModelProperties' => [
                    'user' => $privateUser->getUsername(),
                    'owners' => [
                        $privateUser->getUsername(),
                    ],
                ],
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_LINK_INTEGRITY,
                            'output' => [
                                'output' => json_encode([
                                    [
                                        'context' => '<a href="/">Foo<\/a>',
                                        'state' => 200,
                                        'type' => 'http',
                                        'url' => 'http://example.com/',
                                    ],
                                    [
                                        'context' => '<a href="/bar">Bar<\/a>',
                                        'state' => 404,
                                        'type' => 'http',
                                        'url' => 'http://example.com/bar',
                                    ],
                                    [
                                        'context' => '<a href="/foobar">Foobar<\/a>',
                                        'state' => 404,
                                        'type' => 'http',
                                        'url' => 'http://example.com/foobar',
                                    ],
                                    [
                                        'context' => '<a href="/boo">Boo<\/a>',
                                        'state' => 28,
                                        'type' => 'curl',
                                        'url' => 'http://example.com/boo',
                                    ],
                                    [
                                        'context' => '<a href="/boofar">Boofar<\/a>',
                                        'state' => 28,
                                        'type' => 'curl',
                                        'url' => 'http://example.com/boofar',
                                    ],
                                ]),
                                'content-type' => 'application/json',
                                'error_count' => 5,
                                'warning_count' => 0,
                            ],
                        ]),
                    ]),
                ],
                'user' => $privateUser,
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertStandardViewData($viewName, $parameters);

                            $errorsByLinkState  = $parameters['errors_by_link_state'];

                            $this->assertEquals(
                                [
                                    'http',
                                    'curl',
                                ],
                                array_keys($errorsByLinkState)
                            );

                            $this->assertCount(2, $errorsByLinkState['http'][404]);
                            $this->assertCount(2, $errorsByLinkState['curl'][28]);

                            $this->assertIsArray($parameters['link_class_labels']);
                            $this->assertIsArray($parameters['link_state_descriptions']);

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
        $testModel = $this->createTest();

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var ResultsController $resultsController */
        $resultsController = self::$container->get(ResultsController::class);

        $testRetriever = $this->createTestRetriever(self::TEST_ID, $testModel);

        $this->setTestRetrieverOnController($resultsController, $testRetriever);

        $response = $resultsController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $resultsController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    /**
     * @dataProvider indexActionFailedTaskDataProvider
     *
     * @param array $outputContent
     * @param string|array $expectedHeadingContent
     */
    public function testIndexActionFailedTask(array $outputContent, $expectedHeadingContent)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(true),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([
                array_merge($this->remoteTaskData, [
                    'state' => Task::STATE_FAILED_NO_RETRY_AVAILABLE,
                    'output' => [
                        'output' => json_encode($outputContent),
                        'content-type' => 'application/json',
                        'error_count' => 5,
                        'warning_count' => 0,
                    ],
                ]),
            ]),
        ]);

        $router = self::$container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $crawler = new Crawler($response->getContent());

        $heading = $crawler->filter('h2');

        $headingText = trim($heading->text());

        if (is_string($expectedHeadingContent)) {
            $this->assertEquals($expectedHeadingContent, $headingText);
        }

        if (is_array($expectedHeadingContent)) {
            foreach ($expectedHeadingContent as $contentItem) {
                $this->assertContains($contentItem, $headingText);
            }
        }
    }

    public function indexActionFailedTaskDataProvider(): array
    {
        return [
            'character encoding failure' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'utf-8',
                            'messageId' => 'invalid-character-encoding',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Character Encoding Confusion!',
            ],
            'css validator ssl error' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'SSL Error',
                            'class' => 'css-validation-ssl-error',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'SSL Error!',
            ],
            'css validator unknown error' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Unknown error',
                            'class' => 'css-validation-exception-unknown',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Unknown Validator Error!',
            ],
            'curl DNS timeout' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'DNS lookup failure resolving resource domain name',
                            'messageId' => 'http-retrieval-curl-code-6',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'DNS Timeout!',
            ],
            'curl connection timeout' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Timeout reached retrieving resource',
                            'messageId' => 'http-retrieval-curl-code-28',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Connection Timeout!',
            ],
            'curl ssl connection error' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => '',
                            'messageId' => 'http-retrieval-curl-code-35',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'SSL Error!',
            ],
            'curl malformed URL' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Invalid resource URL',
                            'messageId' => 'http-retrieval-curl-code-3',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Bad URL!',
            ],
            'doctype invalid' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => '',
                            'messageId' => 'document-type-invalid',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Invalid Document Type!',
            ],
            'doctype missing' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'No doctype',
                            'messageId' => 'document-type-missing',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Missing Document Type!',
            ],
            'HTTP 401 authorization required' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Authorization Required',
                            'messageId' => 'http-retrieval-401',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 401',
                    'Authorization',
                    'Required',
                ],
            ],
            'HTTP 403 forbidden' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Forbidden',
                            'messageId' => 'http-retrieval-403',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 403',
                    'Forbidden',
                ],
            ],
            'HTTP 404 not found' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Not found',
                            'messageId' => 'http-retrieval-404',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 404',
                    'Not',
                    'Found',
                ],
            ],
            'HTTP 405 method not allowed' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Method not allowed',
                            'messageId' => 'http-retrieval-405',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 405',
                    'Method',
                    'Not',
                    'Allowed',
                ],
            ],
            'HTTP 500 internal server error' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Internal server error',
                            'messageId' => 'http-retrieval-500',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 500',
                    'Internal',
                    'Server',
                    'Error',
                ],
            ],
            'HTTP 502 bad gateway' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Bad gateway',
                            'messageId' => 'http-retrieval-502',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 502',
                    'Bad',
                    'Gateway',
                ],
            ],
            'HTTP 503 service unavailable' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Service unavailable',
                            'messageId' => 'http-retrieval-503',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => [
                    'HTTP 503',
                    'Service',
                    'Unavailable',
                ],
            ],
            'No markup found in document' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Not markup',
                            'messageId' => 'document-is-not-markup',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'No Markup Found!',
            ],
            'Redirect limit' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Redirect limit of 4 redirects reached',
                            'messageId' => 'http-retrieval-redirect-limit-reached',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Redirect Limit Reached!',
            ],
            'Redirect loop' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'Redirect loop detected',
                            'messageId' => 'http-retrieval-redirect-loop',
                            'type' => 'error',
                        ],
                    ],
                ],
                'expectedHeadingContent' => 'Redirect Loop Detected!',
            ],
        ];
    }

    private function assertParameterData(array $expectedParameterData, array $parameters)
    {
        $this->assertEquals($expectedParameterData['is_public_user_test'], $parameters['is_public_user_test']);
        $this->assertEquals($expectedParameterData['is_owner'], $parameters['is_owner']);
    }

    private function assertStandardViewData(string $viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertCommonViewParameterKeys($parameters);

        $this->assertIsArray($parameters['website_url']);

        /* @var DecoratedTest $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(DecoratedTest::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, $test->getWebsite());

        /* @var Task $task */
        $task = $parameters['task'];
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(self::TASK_ID, $task->getTaskId());
    }

    private function assertCommonViewParameterKeys(array $parameters)
    {
        $expectedKeys = [
            'user',
            'is_logged_in',
            'website_url',
            'test',
            'task',
            'task_url',
            'is_owner',
            'is_public_user_test',
        ];

        $keys = array_keys($parameters);
        foreach ($expectedKeys as $expectedKey) {
            $this->assertContains($expectedKey, $keys);
        }
    }

    private function createTest(array $testModelProperties = []): TestModel
    {
        $testEntity = TestEntity::create(self::TEST_ID);

        $entityManager = self::$container->get(EntityManagerInterface::class);
        $entityManager->persist($testEntity);
        $entityManager->flush();

        $testModel = TestModelFactory::create(array_merge(
            $this->testModelProperties,
            [
                'entity' => $testEntity,
            ],
            $testModelProperties
        ));

        return $testModel;
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
