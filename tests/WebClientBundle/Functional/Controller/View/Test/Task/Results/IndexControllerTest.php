<?php

namespace Tests\WebClientBundle\Functional\Controller\View\Test\Task\Results;

use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\View\Test\Task\Results\IndexController;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Factory\TestFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexControllerTest extends AbstractBaseTestCase
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
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
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
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'invalid owner, not logged in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
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
        $userSerializerService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserSerializerService');

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertContains('<title>Not authorised', $response->getContent());
    }

    public function testIndexActionPublicUserGetRequest()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
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
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRedirect(
        array $httpFixtures,
        User $user,
        Request $request,
        $expectedRedirectUrl,
        $expectedRequestUrls
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

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
            $this->assertEmpty($httpHistory->count());
        } else {
            $requestedUrls = [];

            foreach ($httpHistory as $httpTransaction) {
                /* @var RequestInterface $guzzleRequest */
                $guzzleRequest = $httpTransaction['request'];

                $requestedUrls[] = $guzzleRequest->getUrl();
            }

            $this->assertEquals($expectedRequestUrls, $requestedUrls);
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
                'user' => SystemUserService::getPublicUser(),
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
                'user' => SystemUserService::getPublicUser(),
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
                'user' => SystemUserService::getPublicUser(),
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
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        User $user,
        EngineInterface $templatingEngine
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory($this->container);
            $testFactory->create($testValues);
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'router',
                'SimplyTestable\WebClientBundle\Services\TestService',
                'SimplyTestable\WebClientBundle\Services\RemoteTestService',
                'SimplyTestable\WebClientBundle\Services\UserService',
                'SimplyTestable\WebClientBundle\Services\CacheValidatorService',
                'SimplyTestable\WebClientBundle\Services\UrlViewValuesService',
                'SimplyTestable\WebClientBundle\Services\TaskService',
                'SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService',
                'kernel',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'documentation_site',
                'link_integrity_error_code_map',
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
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
                    ])),
                    HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
                ],
                'user' => SystemUserService::getPublicUser(),
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
                        'user' => SystemUserService::PUBLIC_USER_USERNAME,
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
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
            HttpResponseFactory::createJsonResponse([$this->remoteTaskData]),
        ]);

        $userService = $this->container->get('SimplyTestable\WebClientBundle\Services\UserService');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser(SystemUserService::getPublicUser());

        $request = new Request();

        $this->container->get('request_stack')->push($request);
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
     * @dataProvider indexActionFailedTaskDataProvider
     *
     * @param array $outputContent
     * @param string|array $expectedHeadingContent
     */
    public function testIndexActionFailedTask(array $outputContent, $expectedHeadingContent)
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->remoteTestData),
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

    /**
     * @return array
     */
    public function indexActionFailedTaskDataProvider()
    {
        return [
            'character encoding failure' => [
                'outputContent' => [
                    'messages' => [
                        [
                            'message' => 'foo',
                            'messageId' => 'character-encoding',
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
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME, [
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID,
        ]);
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
