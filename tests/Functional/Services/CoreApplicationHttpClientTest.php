<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use GuzzleHttp\Psr7;
use Psr\Http\Message\ResponseInterface;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidCredentialsException;
use App\Services\CoreApplicationHttpClient;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\HttpMockHandler;
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
     * @var HttpMockHandler
     */
    private $httpMockHandler;

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

        $this->coreApplicationHttpClient = self::$container->get(CoreApplicationHttpClient::class);
        $this->httpMockHandler = self::$container->get(HttpMockHandler::class);
        $this->httpHistory = self::$container->get(HttpHistoryContainer::class);
    }

    /**
     * @dataProvider getRequestThrowsExceptionDataProvider
     */
    public function testGetThrowsException(
        array $httpFixtures,
        array $options,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->get('team_get', [], $options);
    }

    /**
     * @dataProvider getAsAdminRequestThrowsExceptionDataProvider
     */
    public function testGetAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);
    }

    /**
     * @dataProvider postRequestThrowsExceptionDataProvider
     */
    public function testPostThrowsException(
        array $httpFixtures,
        array $options,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->post('team_get', [], [], $options);
    }

    /**
     * @dataProvider postAsAdminRequestThrowsExceptionDataProvider
     */
    public function testPostAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->coreApplicationHttpClient->postAsAdmin('team_get', [], [], $options);
    }

    /**
     * @dataProvider getRequestDoesNotThrowExceptionDataProvider
     */
    public function testGetDoesNotThrowException(
        array $httpFixtures,
        string $userName,
        array $options,
        string $expectedAuthorizationHeaderUser
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->getUserFromUserName($userName));
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->get('team_get', [], $options);
        $this->assertNull($response);

        $authorizationHeader = $this->httpHistory->getLastRequest()->getHeaderLine('authorization');
        $decodedAuthorizationHeader = base64_decode(str_replace('Basic ', '', $authorizationHeader));
        $authorizationHeaderParts = explode(':', $decodedAuthorizationHeader);

        $this->assertEquals($expectedAuthorizationHeaderUser, $authorizationHeaderParts[0]);
    }

    /**
     * @dataProvider postRequestDoesNotThrowExceptionDataProvider
     */
    public function testPostDoesNotThrowException(
        array $httpFixtures,
        string $userName,
        array $options,
        string $expectedAuthorizationHeaderUser
    ) {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($this->getUserFromUserName($userName));
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->post('team_get', [], $options);
        $this->assertNull($response);

        $authorizationHeader = $this->httpHistory->getLastRequest()->getHeaderLine('authorization');
        $decodedAuthorizationHeader = base64_decode(str_replace('Basic ', '', $authorizationHeader));
        $authorizationHeaderParts = explode(':', $decodedAuthorizationHeader);

        $this->assertEquals($expectedAuthorizationHeaderUser, $authorizationHeaderParts[0]);
    }

    /**
     * @dataProvider requestSuccessDataProvider
     */
    public function testGetSuccess(array $httpFixtures, array $options, ?ResponseInterface $expectedResponse)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

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
     */
    public function testGetAsAdminSuccess(array $httpFixtures, array $options, ?ResponseInterface $expectedResponse)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

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
     */
    public function testPostSuccess(array $httpFixtures, array $options, ?ResponseInterface $expectedResponse)
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $response = $this->coreApplicationHttpClient->post('team_get', [], $postData, $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response));
        }

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($postData, $postedData);
    }

    /**
     * @dataProvider requestSuccessDataProvider
     */
    public function testPostAsAdminSuccess(array $httpFixtures, array $options, ?ResponseInterface $expectedResponse)
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->httpMockHandler->appendFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->postAsAdmin('team_get', [], $postData, $options);

        if (empty($expectedResponse)) {
            $this->assertNull($response);
        } else {
            $this->assertEquals(Psr7\str($expectedResponse), Psr7\str($response));
        }

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals($postData, $postedData);
    }

    public function requestSuccessDataProvider(): array
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'foo' => 'bar',
            ]),
        ]);

        $this->coreApplicationHttpClient->get('user', [
            'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
        ]);

        $this->assertEquals('http://null/user/public/', $this->httpHistory->getLastRequest()->getUri());
    }

    public function getRequestDoesNotThrowExceptionDataProvider(): array
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

    public function postRequestDoesNotThrowExceptionDataProvider(): array
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

    public function getRequestThrowsExceptionDataProvider(): array
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

    public function getAsAdminRequestThrowsExceptionDataProvider(): array
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

    public function postAsAdminRequestThrowsExceptionDataProvider(): array
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

    public function postRequestThrowsExceptionDataProvider(): array
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

    public function requestThrowsExceptionDataProvider(): array
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

    private function getUserFromUserName(string $userName): User
    {
        $systemUserService = self::$container->get(SystemUserService::class);

        $user = SystemUserService::getPublicUser();

        if ('private' === $userName) {
            $user = new User(self::USER_EMAIL);
        } elseif ('admin' === $userName) {
            $user = $systemUserService->getAdminUser();
        }

        return $user;
    }
}
