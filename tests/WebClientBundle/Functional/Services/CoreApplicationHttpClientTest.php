<?php

namespace Tests\WebClientBundle\Functional\Services;

use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\HttpClientFactory;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\TestHttpClientFactory;
use webignition\SimplyTestableUserModel\User;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class CoreApplicationHttpClientTest extends AbstractBaseTestCase
{
    const USER_EMAIL = 'user@example.com';
    const PUBLIC_USER_AUTHORIZATION_HEADER = 'Basic cHVibGljOnB1YmxpYw==';
    const ADMIN_USER_AUTHORIZATION_HEADER = 'Basic YWRtaW46YnR3cEF6bTI=';

    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var HttpHistoryContainer
     */
    private $httpHistory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $this->httpHistory = $this->container->get(HttpHistoryContainer::class);
    }

    /**
     * @dataProvider getRequestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testGetThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->get('team_get', [], $options);
    }

    /**
     * @dataProvider getAsAdminRequestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function testGetAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);
    }

    /**
     * @dataProvider postRequestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testPostThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->post('team_get', [], [], $options);
    }

    /**
     * @dataProvider postAsAdminRequestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function testPostAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->postAsAdmin('team_get', [], [], $options);
    }

    /**
     * @dataProvider getRequestDoesNotThrowExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $userName
     * @param array $options
     * @param string $expectedAuthorizationHeaderUser
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testGetDoesNotThrowException(
        array $httpFixtures,
        $userName,
        array $options,
        $expectedAuthorizationHeaderUser
    ) {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->get('team_get', [], $options);
        $this->assertNull($response);

        $authorizationHeader = $this->httpHistory->getLastRequest()->getHeaderLine('authorization');
        $decodedAuthorizationHeader = base64_decode(str_replace('Basic ', '', $authorizationHeader));
        $authorizationHeaderParts = explode(':', $decodedAuthorizationHeader);

        $this->assertEquals($expectedAuthorizationHeaderUser, $authorizationHeaderParts[0]);
    }

    /**
     * @dataProvider postRequestDoesNotThrowExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $userName
     * @param array $options
     * @param string $expectedAuthorizationHeaderUser
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testPostDoesNotThrowException(
        array $httpFixtures,
        $userName,
        array $options,
        $expectedAuthorizationHeaderUser
    ) {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->post('team_get', [], $options);
        $this->assertNull($response);

        $authorizationHeader = $this->httpHistory->getLastRequest()->getHeaderLine('authorization');
        $decodedAuthorizationHeader = base64_decode(str_replace('Basic ', '', $authorizationHeader));
        $authorizationHeaderParts = explode(':', $decodedAuthorizationHeader);

        $this->assertEquals($expectedAuthorizationHeaderUser, $authorizationHeaderParts[0]);
    }

    /**
     * @dataProvider requestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param ResponseInterface|null $expectedResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testGetSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response1 = $this->coreApplicationHttpClient->get('team_get', [], $options);
        $response2 = $this->coreApplicationHttpClient->get('team_get', [], $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response1);
            $this->assertNull($response2);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response1));
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response2));
        }
    }

    /**
     * @dataProvider requestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     *
     * @param ResponseInterface|null $expectedResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function testGetAsAdminSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response1 = $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);
        $response2 = $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response1);
            $this->assertNull($response2);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response1));
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response2));
        }
    }

    /**
     * @dataProvider requestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     *
     * @param ResponseInterface|null $expectedResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testPostSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        /* @var TestHttpClientFactory $httpClientFactory */
        $httpClientFactory = $this->container->get(HttpClientFactory::class);

        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);
        $response = $this->coreApplicationHttpClient->post('team_get', [], $postData, $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response));
        }

        $lastRequest = $httpClientFactory->getHistory()->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($postData, $postedData);
    }

    /**
     * @dataProvider requestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     *
     * @param mixed $expectedResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function testPostAsAdminSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        /* @var TestHttpClientFactory $httpClientFactory */
        $httpClientFactory = $this->container->get(HttpClientFactory::class);

        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->postAsAdmin('team_get', [], $postData, $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response));
        }

        $lastRequest = $httpClientFactory->getHistory()->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($postData, $postedData);
    }

    /**
     * @return array
     */
    public function requestSuccessDataProvider()
    {
        $successResponse = HttpResponseFactory::createSuccessResponse([], 'foo');
        $redirectResponse = HttpResponseFactory::createRedirectResponse();

        return [
            '200; empty body' => [
                'httpFixtures' => [
                    $successResponse,
                ],
                'options' => [],
                'expectedResponse' => $successResponse,
            ],
            '302 with redirects disallowed' => [
                'httpFixtures' => [
                    $redirectResponse,
                ],
                'options' => [
                    CoreApplicationHttpClient::OPT_DISABLE_REDIRECT,
                ],
                'expectedResponse' => $redirectResponse,
            ],
        ];
    }

    public function testPreProcessRouteParameters()
    {
        /* @var TestHttpClientFactory $httpClientFactory */
        $httpClientFactory = $this->container->get(HttpClientFactory::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'foo' => 'bar',
            ]),
        ]);

        $this->coreApplicationHttpClient->get('user', [
            'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
        ]);

        $this->assertEquals('http://null/user/public/', $httpClientFactory->getHistory()->getLastRequest()->getUri());
    }

    /**
     * @return array
     */
    public function getRequestDoesNotThrowExceptionDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();

        return array_merge([
            'read only' => [
                'httpFixtures' => array_fill(0, 6, $serviceUnavailableResponse),
                'user' => 'public',
                'options' => [],
                'expectedAuthorizationHeaderUser' => 'public',
            ],
        ], $this->postRequestDoesNotThrowExceptionDataProvider());
    }

    /**
     * @return array
     */
    public function postRequestDoesNotThrowExceptionDataProvider()
    {
        return [
            'invalid admin credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'user' => 'admin',
                'options' => [],
                'expectedAuthorizationHeaderUser' => 'admin',
            ],
            'invalid admin credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'user' => 'admin',
                'options' => [],
                'expectedAuthorizationHeaderUser' => 'admin',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getRequestThrowsExceptionDataProvider()
    {
        return array_merge([
            'invalid user credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'options' => [],
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid user credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'options' => [],
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ], $this->requestThrowsExceptionDataProvider());
    }

    /**
     * @return array
     */
    public function getAsAdminRequestThrowsExceptionDataProvider()
    {
        return array_merge([
            'invalid admin credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'options' => [],
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid admin credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'options' => [],
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ], $this->requestThrowsExceptionDataProvider());
    }

    /**
     * @return array
     */
    public function postAsAdminRequestThrowsExceptionDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();

        return array_merge([
            'read only' => [
                'httpFixtures' => array_fill(0, 4, $serviceUnavailableResponse),
                'options' => [],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ], $this->getAsAdminRequestThrowsExceptionDataProvider());
    }

    /**
     * @return array
     */
    public function postRequestThrowsExceptionDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();

        return array_merge([
            'read only' => [
                'httpFixtures' => array_fill(0, 4, $serviceUnavailableResponse),
                'options' => [],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ], $this->getRequestThrowsExceptionDataProvider());
    }

    /**
     * @return array
     */
    public function requestThrowsExceptionDataProvider()
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curl28Exception = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            '404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            '500' => [
                'httpFixtures' => array_fill(0, 6, $internalServerErrorResponse),
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'curl exception' => [
                'httpFixtures' => array_fill(0, 6, $curl28Exception),
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }


    /**
     * @param string $userName
     *
     * @return User
     */
    private function getUserFromUserName($userName)
    {
        $systemUserService = $this->container->get(SystemUserService::class);

        $user = SystemUserService::getPublicUser();

        if ('private' === $userName) {
            $user = new User(self::USER_EMAIL);
        } elseif ('admin' === $userName) {
            $user = $systemUserService->getAdminUser();
        }

        return $user;
    }
}
