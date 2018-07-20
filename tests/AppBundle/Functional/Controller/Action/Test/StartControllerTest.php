<?php

namespace Tests\AppBundle\Functional\Controller\Action\Test;

use Mockery\Mock;
use AppBundle\Controller\Action\Test\StartController;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Model\RemoteTest\RemoteTest;
use AppBundle\Services\Configuration\LinkIntegrityTestConfiguration;
use AppBundle\Services\Configuration\TestOptionsConfiguration;
use AppBundle\Services\RemoteTestService;
use AppBundle\Services\SystemUserService;
use AppBundle\Services\TaskTypeService;
use AppBundle\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use AppBundle\Services\UserManager;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Tests\AppBundle\Functional\Controller\AbstractControllerTest;
use Tests\AppBundle\Services\HttpMockHandler;
use webignition\SimplyTestableUserModel\User;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class StartControllerTest extends AbstractControllerTest
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var StartController
     */
    private $testStartController;

    /**
     * @var HttpHistoryContainer
     */
    private $httpHistory;

    /**
     * @var HttpMockHandler
     */
    private $httpMockHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testStartController = self::$container->get(StartController::class);
        $this->httpHistory = self::$container->get(HttpHistoryContainer::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
    }

    public function testStartNewActionGetRequest()
    {
        $this->client->request(
            'GET',
            $this->router->generate('test_start')
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @dataProvider startNewActionDataProvider
     *
     * @param array $httpFixtures
     * @param User $user
     * @param Request $request
     * @param string $expectedRedirectUrl
     * @param array $expectedFlashBagValues
     * @param string $expectedRequestUrl
     * @param array $expectedPostData
     *
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testStartNewAction(
        array $httpFixtures,
        User $user,
        Request $request,
        $expectedRedirectUrl,
        array $expectedFlashBagValues,
        $expectedRequestUrl,
        array $expectedPostData = []
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $session = self::$container->get('session');
        $flashBag = $session->getFlashBag();

        /* @var RedirectResponse $response */
        $response = $this->testStartController->startNewAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(urldecode($expectedRedirectUrl), urldecode($response->getTargetUrl()));

        foreach ($expectedFlashBagValues as $key => $value) {
            $this->assertEquals($value, $flashBag->get($key));
        }

        $lastRequest = $this->httpHistory->getLastRequest();

        if (empty($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUri());

            $postedData = [];
            parse_str($lastRequest->getBody()->getContents(), $postedData);

            $this->assertEquals($expectedPostData, $postedData);
        }
    }

    /**
     * @return array
     */
    public function startNewActionDataProvider()
    {
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User(self::USER_EMAIL);

        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        $expectedProgressRedirectUrl = '/http://example.com//1/progress/';

        return [
            'website missing' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request(),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrl' => null,
            ],
            'website empty; empty string' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => '',
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrl' => null,
            ],
            'website empty; whitespace-only string' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => '   ',
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'website-blank',
                    ],
                ],
                'expectedRequestUrl' => null,
            ],
            'no task types selected' => [
                'httpFixtures' => [],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'website' => self::WEBSITE,
                    'html-validation' => 0,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'no-test-types-selected',
                    ],
                ],
                'expectedRequestUrl' => null,
            ],
            'curl exception' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'curl-error',
                    ],
                    'curl_error_code' => [
                        28,
                    ],
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'css-validation' => 0,
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'web_resource_exception',
                    ],
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'HTTP 500; with http auth' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => 'user',
                    'http-auth-password' => 'pass',
                ]),
                'expectedRedirectUrl' => $this->createExpectedStartFailureRedirectUrl([
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'http-auth-username' => 'user',
                ]),
                'expectedFlashBagValues' => [
                    'test_start_error' => [
                        'web_resource_exception',
                    ],
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                    'parameters' => [
                        'http-auth-username' => 'user',
                        'http-auth-password' => 'pass',
                    ],
                ],
            ],
            'success; type=full site' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'success; type=single' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'full-single' => 'single',
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'single url',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'success; private user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $privateUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                    'test-type-options' => [
                        'JS static analysis' => [
                            'jslint-option-bitwise' => 1,
                            'jslint-option-continue' => 1,
                            'jslint-option-debug' => 1,
                            'jslint-option-evil' => 1,
                            'jslint-option-eqeq' => 1,
                            'jslint-option-forin' => 1,
                            'jslint-option-newcap' => 1,
                            'jslint-option-nomen' => 1,
                            'jslint-option-plusplus' => 1,
                            'jslint-option-regexp' => 1,
                            'jslint-option-unparam' => 1,
                            'jslint-option-sloppy' => 1,
                            'jslint-option-stupid' => 1,
                            'jslint-option-sub' => 1,
                            'jslint-option-vars' => 1,
                            'jslint-option-white' => 1,
                            'jslint-option-anon' => 1,
                        ],
                    ],
                ],
            ],
            'success; private user; link integrity' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $privateUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'link-integrity' => 1,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'Link integrity',
                    ],
                    'test-type-options' => [
                        'JS static analysis' => [
                            'jslint-option-bitwise' => 1,
                            'jslint-option-continue' => 1,
                            'jslint-option-debug' => 1,
                            'jslint-option-evil' => 1,
                            'jslint-option-eqeq' => 1,
                            'jslint-option-forin' => 1,
                            'jslint-option-newcap' => 1,
                            'jslint-option-nomen' => 1,
                            'jslint-option-plusplus' => 1,
                            'jslint-option-regexp' => 1,
                            'jslint-option-unparam' => 1,
                            'jslint-option-sloppy' => 1,
                            'jslint-option-stupid' => 1,
                            'jslint-option-sub' => 1,
                            'jslint-option-vars' => 1,
                            'jslint-option-white' => 1,
                            'jslint-option-anon' => 1,
                        ],
                        'Link integrity' => [
                            'excluded-domains' => [
                                'instagram.com',
                            ],
                        ],
                    ],
                ],
            ],
            'success; public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'success; public user; schemeless website' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => 'example.com/',
                    'html-validation' => 1,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'success; public user; http auth' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => 'user',
                    'http-auth-password' => 'pass',
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                    'parameters' => [
                        'http-auth-username' => 'user',
                        'http-auth-password' => 'pass',
                    ],
                ],
            ],
            'success; public user; empty http auth' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'http-auth-username' => '',
                    'http-auth-password' => '',
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                ],
            ],
            'success; public user; cookies' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::TEST_ID,
                        'website' => self::WEBSITE,
                    ]),
                ],
                'user' => $publicUser,
                'request' => new Request([], [
                    'website' => self::WEBSITE,
                    'html-validation' => 1,
                    'cookies' => [
                        [
                            'name' => 'cookie-name-1',
                            'value' => 'cookie-value-1',
                        ],
                    ],
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/start/',
                'expectedPostData' => [
                    'type' => 'full site',
                    'test-types' => [
                        'HTML validation',
                    ],
                    'parameters' => [
                        'cookies' => [
                            [
                                'name' => 'cookie-name-1',
                                'value' => 'cookie-value-1',
                                'path' => '/',
                                'domain' => '.example.com',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider startNewActionVerifyUrlToTestDataProvider
     *
     * @param string $url
     * @param string $expectedUrlToTest
     *
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testStartNewActionVerifyUrlToTest($url, $expectedUrlToTest)
    {
        $router = self::$container->get('router');
        $testOptionsRequestAdapterFactory = self::$container->get(TestOptionsRequestAdapterFactory::class);
        $taskTypeService = self::$container->get(TaskTypeService::class);
        $userManager = self::$container->get(UserManager::class);
        $linkIntegrityTestConfiguration = self::$container->get(LinkIntegrityTestConfiguration::class);
        $testOptionsConfiguration = self::$container->get(TestOptionsConfiguration::class);
        $session = self::$container->get('session');

        /* @var RemoteTestService|Mock $remoteTestService */
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('start')
            ->withArgs(function ($urlToTest) use ($expectedUrlToTest) {
                $this->assertEquals($expectedUrlToTest, $urlToTest);

                return true;
            })
            ->andReturn(new RemoteTest([
                'id' => 1,
                'website' => $expectedUrlToTest,
            ]));

        $testStartController = new StartController(
            $router,
            $remoteTestService,
            $testOptionsRequestAdapterFactory,
            $taskTypeService,
            $userManager,
            $linkIntegrityTestConfiguration,
            $testOptionsConfiguration,
            $session
        );

        $requestData = [
            'html-validation' => 1,
            'website' => $url,
        ];

        $request = new Request([], $requestData);

        $testStartController->startNewAction($request);
    }

    /**
     * @return array
     */
    public function startNewActionVerifyUrlToTestDataProvider()
    {
        return [
            'valid url; http://example.com/' => [
                'url' => 'http://example.com/',
                'expectedUrlToTest' => 'http://example.com/',
            ],
            'valid url; //example.com/' => [
                'url' => '//example.com/',
                'expectedUrlToTest' => 'http://example.com/',
            ],
            'valid url; example.com/' => [
                'url' => 'example.com/',
                'expectedUrlToTest' => 'http://example.com/',
            ],
            'invalid url; foo' => [
                'url' => 'foo',
                'expectedUrlToTest' => 'foo',
            ],
            'invalid url; linux-like path' => [
                'url' => '/home/foo/file.html',
                'expectedUrlToTest' => '/home/foo/file.html',
            ],
            'invalid url; windows-like path' => [
                'url' => 'c:\\Users\\Desktop\\file.html',
                'expectedUrlToTest' => 'c:\\Users\\Desktop\\file.html',
            ],
            'invalid url; not even close' => [
                'url' => 'vertical-align:top',
                'expectedUrlToTest' => 'vertical-align:top',
            ],
        ];
    }

    /**
     * @param array $queryStringParameters
     *
     * @return string
     */
    private function createExpectedStartFailureRedirectUrl(array $queryStringParameters)
    {
        return '/?' . http_build_query($queryStringParameters);
    }
}