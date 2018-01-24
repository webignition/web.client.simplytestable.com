<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results;

use SimplyTestable\WebClientBundle\Controller\View\Test\Task\Results\IndexController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpHistory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends BaseSimplyTestableTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/Test/Task/Results/Index:index.html.twig';
    const ROUTE_NAME = 'view_test_task_results_index_index';

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const TASK_ID = 2;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

    /**
     * @var array
     */
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
                'name' => Task::TYPE_JS_STATIC_ANALYSIS,
            ],
            [
                'name' => Task::TYPE_LINK_INTEGRITY,
            ],
        ],
        'user' => self::USER_EMAIL,
        'state' => Test::STATE_COMPLETED,
        'task_type_options' => [],
        'task_count' => 12,
    ];

    /**
     * @var array
     */
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

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setHttpFixtures($httpFixtures);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function indexActionInvalidGetRequestDataProvider()
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(404),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'invalid owner, not logged in' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedRedirectUrl' => sprintf(
                    'http://localhost/signin/?redirect=%s%s',
                    'eyJyb3V0ZSI6InZpZXdfdGVzdF9wcm9ncmVzc19pbmRleF9pbmRleCIsInBhcmFtZXRlcnMiOnsid2Vic2l0ZSI6I',
                    'mh0dHA6XC9cL2V4YW1wbGUuY29tXC8iLCJ0ZXN0X2lkIjoiMSJ9fQ%3D%3D'
                ),
            ],
        ];
    }

    public function testIndexActionInvalidTestOwnerIsLoggedIn()
    {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID,
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Not authorised', $response->getContent());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $router = $this->container->get('router');
        $requestUrl = $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var Response $response */
        $response = $this->client->getResponse();
        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRedirectDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param string[] $expectedRequestUrls
     *
     * @throws WebResourceException
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        $httpHistory = new HttpHistory($this->container->get('simplytestable.services.httpclientservice'));

        $this->setHttpFixtures($httpFixtures);

        $this->indexController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->indexController->indexAction(
            $request,
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());

        if (empty($expectedRequestUrls)) {
            $this->assertEquals(0, $httpHistory->count());
        } else {
            $this->assertEquals($expectedRequestUrls, $httpHistory->getRequestUrls());
        }
    }

    /**
     * @return array
     */
    public function indexActionRedirectDataProvider()
    {
        return [
            'invalid task' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'id' => 3,
                        ]),
                    ]),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'task has no errors and no warnings' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
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
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
            'incomplete task' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'state' => Test::STATE_IN_PROGRESS,
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'state' => Task::STATE_IN_PROGRESS,
                        ]),
                    ]),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'request' => new Request(),
                'expectedRedirectUrl' => 'http://localhost/http://example.com//1/',
                'expectedRequestUrls' => [
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
                    'http://null/job/http%3A%2F%2Fexample.com%2F/1/tasks/',
                ],
            ],
        ];
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param EngineInterface $templatingEngine
     *
     * @throws WebResourceException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        EngineInterface $templatingEngine
    ) {
        $userService = $this->container->get('simplytestable.services.userservice');
        $userService->setUser($user);

        $this->setHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'simplytestable.services.testservice',
                'simplytestable.services.remotetestservice',
                'simplytestable.services.userservice',
                'simplytestable.services.cachevalidator',
                'simplytestable.services.urlviewvalues',
                'simplytestable.services.taskservice',
                'simplytestable.services.documentationurlcheckerservice',
                'kernel',
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
                'documentation_site',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction(
            new Request(),
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );
        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'public user, public test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->remoteTestData, [
                        'user' => UserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
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
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
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
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $expectedError1Url = 'http://help.simplytestable.com/errors/html-validation/an-img-'
                                .'element-must-have-an-alt-attribute-except-under-certain-conditions-for-details-'
                                .'consult-guidance-on-providing-text-alternatives-for-images/';

                            $expectedError2Url = 'http://help.simplytestable.com/errors/html-validation/ampersand-did-'
                                .'not-start-a-character-reference-ampersand-probably-should-have-been-'
                                .'escaped-as-amp/';

                            $expectedError3Url = 'http://help.simplytestable.com/errors/html-validation/required-'
                                .'attribute-x-not-specified/required-attribute-alt-not-specified/';

                            $expectedError4Url = 'http://help.simplytestable.com/errors/html-validation/forbidden-'
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_CSS_VALIDATION,
                        ]),
                    ]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertArrayHasKey('errors_by_ref', $parameters);
                            $this->assertArrayHasKey('warnings_by_ref', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'css validation; has errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
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
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
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
            'js static analysis; view keys' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_JS_STATIC_ANALYSIS,
                        ]),
                    ]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertArrayHasKey('errors_by_js_context', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'js static analysis; has errors' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_JS_STATIC_ANALYSIS,
                            'output' => [
                                'output' => json_encode([
                                    'http://example.com/foo.js' => [
                                        'statusLine' => 'http://example.com/foo.js',
                                        'entries' => [
                                            [
                                                'headerLine' => [
                                                    'errorNumber' => 1,
                                                    'errorMessage' => "'$' was used before it was defined.",
                                                ],
                                                'fragmentLine' => [
                                                    'fragment' => '$(function(){',
                                                    'lineNumber' => 1,
                                                    'columnNumber' => 1,
                                                ],
                                            ],
                                            [
                                                'headerLine' => [
                                                    'errorNumber' => 1,
                                                    'errorMessage' => "Expected ';' and instead saw '}'.",
                                                ],
                                                'fragmentLine' => [
                                                    'fragment' => '                })',
                                                    'lineNumber' => 1,
                                                    'columnNumber' => 1,
                                                ],
                                            ],
                                        ],
                                    ],
                                    'http://example.com/bar.js' => [
                                        'entries' => [
                                            [
                                                'headerLine' => [
                                                    'errorNumber' => 1,
                                                    'errorMessage' => "'_gaq' used out of scope.",
                                                ],
                                                'fragmentLine' => [
                                                    'fragment' => 'var _gaq = _gaq || [];',
                                                    'lineNumber' => 1,
                                                    'columnNumber' => 1,
                                                ],
                                            ],
                                        ],
                                    ],
                                ]),
                                'content-type' => 'application/json',
                                'error_count' => 2,
                                'warning_count' => 1,
                            ],
                        ]),
                    ]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertArrayHasKey('errors_by_js_context', $parameters);

                            $errorsByJsContext = $parameters['errors_by_js_context'];
                            $this->assertEquals(
                                [
                                    'http://example.com/foo.js',
                                    'http://example.com/bar.js',
                                ],
                                array_keys($errorsByJsContext)
                            );

                            $this->assertCount(2, $errorsByJsContext['http://example.com/foo.js']);
                            $this->assertCount(1, $errorsByJsContext['http://example.com/bar.js']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'link integrity; view keys' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
                    HttpResponseFactory::createJsonResponse([
                        array_merge($this->remoteTaskData, [
                            'type' => Task::TYPE_LINK_INTEGRITY,
                        ]),
                    ]),
                ],
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
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
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->remoteTestData),
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
                'user' => new User(self::USER_EMAIL),
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
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

                            $this->assertInternalType('array', $parameters['link_class_labels']);
                            $this->assertInternalType('array', $parameters['link_state_descriptions']);

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
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $request = new Request();

        $this->container->set('request', $request);
        $this->indexController->setContainer($this->container);

        $response = $this->indexController->indexAction(
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
        $newResponse = $this->indexController->indexAction(
            $newRequest,
            self::WEBSITE,
            self::TEST_ID,
            self::TASK_ID
        );

        $this->assertInstanceOf(Response::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }


    /**
     * @param array $expectedParameterData
     * @param array $parameters
     */
    private function assertParameterData($expectedParameterData, $parameters)
    {
        $this->assertEquals($expectedParameterData['is_public_user_test'], $parameters['is_public_user_test']);
        $this->assertEquals($expectedParameterData['is_owner'], $parameters['is_owner']);
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertStandardViewData($viewName, array $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertCommonViewParameterKeys($parameters);

        $this->assertInternalType('array', $parameters['website_url']);

        /* @var Test $test */
        $test = $parameters['test'];
        $this->assertInstanceOf(Test::class, $test);
        $this->assertEquals(self::TEST_ID, $test->getTestId());
        $this->assertEquals(self::WEBSITE, (string)$test->getWebsite());

        /* @var Task $task */
        $task = $parameters['task'];
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals(self::TASK_ID, $task->getTaskId());
    }

    /**
     * @param array $parameters
     */
    private function assertCommonViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'public_site',
                'external_links',
                'website_url',
                'test',
                'task',
                'task_url',
                'is_owner',
                'is_public_user_test',
            ],
            array_keys($parameters)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
