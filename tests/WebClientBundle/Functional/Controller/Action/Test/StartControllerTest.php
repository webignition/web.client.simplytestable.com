<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\Test;

use GuzzleHttp\Post\PostBody;
use GuzzleHttp\Subscriber\History as HttpHistorySubscriber;
use SimplyTestable\WebClientBundle\Controller\Action\Test\StartController;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class StartControllerTest extends AbstractBaseTestCase
{
    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;
    const USER_EMAIL = 'user@example.com';

    /**
     * @var StartController
     */
    private $testStartController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testStartController = $this->container->get(StartController::class);
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
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($user);

        $httpHistory = new HttpHistorySubscriber();
        $coreApplicationHttpClient->getHttpClient()->getEmitter()->attach($httpHistory);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $session = $this->container->get('session');
        $flashBag = $session->getFlashBag();

        /* @var RedirectResponse $response */
        $response = $this->testStartController->startNewAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(urldecode($expectedRedirectUrl), urldecode($response->getTargetUrl()));

        foreach ($expectedFlashBagValues as $key => $value) {
            $this->assertEquals($value, $flashBag->get($key));
        }

        $lastRequest = $httpHistory->getLastRequest();

        if (empty($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());

            /* @var PostBody $requestBody */
            $requestBody = $lastRequest->getBody();

            $this->assertEquals($expectedPostData, $requestBody->getFields());
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                        'HTML validation' => [],
                        'CSS validation' => [],
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
                        'Link integrity' => [],
                    ],
                    'parameters' => [],
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
                        'HTML validation' => [],
                        'CSS validation' => [],
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
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
                    ],
                    'parameters' => [],
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
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [],
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
     * @param array $queryStringParameters
     *
     * @return string
     */
    private function createExpectedStartFailureRedirectUrl(array $queryStringParameters)
    {
        return '/?' . http_build_query($queryStringParameters);
    }
}
