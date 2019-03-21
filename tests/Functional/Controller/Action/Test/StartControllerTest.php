<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\Test;

use App\Entity\Test;
use App\Model\TestIdentifier;
use GuzzleHttp\Psr7\Response;
use Mockery\Mock;
use App\Controller\Action\Test\StartController;
use App\Services\Configuration\LinkIntegrityTestConfiguration;
use App\Services\Configuration\TestOptionsConfiguration;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\UserManager;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\Controller\AbstractControllerTest;
use App\Tests\Services\HttpMockHandler;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
        $this->httpMockHandler->appendFixtures([new Response()]);

        $this->client->request(
            'GET',
            $this->router->generate('action_test_start')
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @dataProvider startNewActionDataProvider
     */
    public function testStartNewAction(
        array $httpFixtures,
        User $user,
        Request $request,
        string $expectedRedirectUrl,
        array $expectedFlashBagValues,
        ?string $expectedRequestUrl,
        array $expectedPostData = []
    ) {
        $userManager = self::$container->get(UserManager::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $userManager->setUser($user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

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

    public function startNewActionDataProvider(): array
    {
        $publicUser = SystemUserService::getPublicUser();
        $privateUser = new User(self::USER_EMAIL);

        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'parameters' => [
                        'http-auth-username' => 'user',
                        'http-auth-password' => 'pass',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_SINGLE_URL,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'Link integrity',
                    ],
                    'test-type-options' => [
                        'Link integrity' => [
                            'excluded-domains' => [
                                'instagram.com',
                            ],
                        ],
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'parameters' => [
                        'http-auth-username' => 'user',
                        'http-auth-password' => 'pass',
                    ],
                    'url' => self::WEBSITE,
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
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
                    'test-types' => [
                        'HTML validation',
                    ],
                    'url' => self::WEBSITE,
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
                    'url' => self::WEBSITE,
                ]),
                'expectedRedirectUrl' => $expectedProgressRedirectUrl,
                'expectedFlashBagValues' => [],
                'expectedRequestUrl' => 'http://null/job/start/',
                'expectedPostData' => [
                    'type' => TestModel::TYPE_FULL_SITE,
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
                    'url' => self::WEBSITE,
                ],
            ],
        ];
    }

    /**
     * @dataProvider startNewActionVerifyUrlToTestDataProvider
     */
    public function testStartNewActionVerifyUrlToTest(string $url, string $expectedUrlToTest)
    {
        $router = self::$container->get('router');

        /* @var TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory */
        $testOptionsRequestAdapterFactory = self::$container->get(TestOptionsRequestAdapterFactory::class);
        $linkIntegrityTestConfiguration = self::$container->get(LinkIntegrityTestConfiguration::class);
        $testOptionsConfiguration = self::$container->get(TestOptionsConfiguration::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        /* @var RemoteTestService|Mock $remoteTestService */
        $remoteTestService = \Mockery::mock(RemoteTestService::class);
        $remoteTestService
            ->shouldReceive('start')
            ->withArgs(function ($urlToTest) use ($expectedUrlToTest) {
                $this->assertEquals($expectedUrlToTest, $urlToTest);

                return true;
            })
            ->andReturn(new TestIdentifier(
                1,
                $expectedUrlToTest
            ));

        $testStartController = new StartController(
            $router,
            $remoteTestService,
            $testOptionsRequestAdapterFactory,
            $linkIntegrityTestConfiguration,
            $testOptionsConfiguration,
            $flashBag
        );

        $requestData = [
            'html-validation' => 1,
            'website' => $url,
        ];

        $request = new Request([], $requestData);

        $testStartController->startNewAction($request);
    }

    public function startNewActionVerifyUrlToTestDataProvider(): array
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

    private function createExpectedStartFailureRedirectUrl(array $queryStringParameters): string
    {
        return '/?' . http_build_query($queryStringParameters);
    }
}
