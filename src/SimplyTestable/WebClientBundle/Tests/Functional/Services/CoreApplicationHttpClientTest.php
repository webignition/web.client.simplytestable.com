<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

class CoreApplicationHttpClientTest extends AbstractBaseTestCase
{
    const USER_EMAIL = 'user@example.com';

    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
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
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

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
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testGetAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

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
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testPostThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

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
     * @throws InvalidContentTypeException
     */
    public function testPostAsAdminThrowsException(
        array $httpFixtures,
        array $options,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->coreApplicationHttpClient->postAsAdmin('team_get', [], [], $options);
    }

    /**
     * @dataProvider getRequestDoesNotThrowExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $userName
     * @param array $options
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetDoesNotThrowException(array $httpFixtures, $userName, array $options)
    {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->get('team_get', [], $options);
        $this->assertNull($response);
    }

    /**
     * @dataProvider postRequestDoesNotThrowExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $userName
     * @param array $options
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws CoreApplicationReadOnlyException
     */
    public function testPostDoesNotThrowException(array $httpFixtures, $userName, array $options)
    {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $response = $this->coreApplicationHttpClient->post('team_get', [], $options);
        $this->assertNull($response);
    }

    /**
     * @dataProvider requestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     *
     * @param mixed $expectedResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response1 = $this->coreApplicationHttpClient->get('team_get', [], $options);
        $response2 = $this->coreApplicationHttpClient->get('team_get', [], $options);

        $this->assertEquals($response1, $response2);
        $this->assertEquals($expectedResponse, $response1);
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
     * @throws InvalidContentTypeException
     */
    public function testGetAsAdminSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response1 = $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);
        $response2 = $this->coreApplicationHttpClient->getAsAdmin('team_get', [], $options);

        $this->assertEquals($response1, $response2);
        $this->assertEquals($expectedResponse, $response1);
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
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testPostSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $httpHistory = new HistoryPlugin();

        $this->coreApplicationHttpClient->getHttpClient()->addSubscriber($httpHistory);
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response = $this->coreApplicationHttpClient->post('team_get', [], $postData, $options);

        $this->assertEquals($expectedResponse, $response);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $httpHistory->getLastRequest();

        $this->assertEquals($postData, $lastRequest->getPostFields()->getAll());
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
     * @throws InvalidContentTypeException
     */
    public function testPostAsAdminSuccess(array $httpFixtures, array $options, $expectedResponse)
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $httpHistory = new HistoryPlugin();

        $this->coreApplicationHttpClient->getHttpClient()->addSubscriber($httpHistory);
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response = $this->coreApplicationHttpClient->postAsAdmin('team_get', [], $postData, $options);

        $this->assertEquals($expectedResponse, $response);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $httpHistory->getLastRequest();

        $this->assertEquals($postData, $lastRequest->getPostFields()->getAll());
    }

    /**
     * @return array
     */
    public function requestSuccessDataProvider()
    {
        $emptyResponse = new Response(200);
        $jsonData = [
            'foo' => 'bar',
        ];

        return [
            '200; empty body' => [
                'httpFixtures' => [
                    $emptyResponse,
                ],
                'options' => [],
                'expectedResponse' => $emptyResponse,
            ],
            '404 treated as empty' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'options' => [
                    CoreApplicationHttpClient::OPT_TREAT_404_AS_EMPTY => true,
                ],
                'expectedResponse' => null,
            ],
            'json' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($jsonData),
                ],
                'options' => [
                    CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                ],
                'expectedResponse' => $jsonData,
            ],
        ];
    }

    public function testPreProcessRouteParameters()
    {
        $httpHistoryPlugin = new HistoryPlugin();

        $httpClient = $this->coreApplicationHttpClient->getHttpClient();
        $httpClient->addSubscriber($httpHistoryPlugin);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'foo' => 'bar',
            ]),
        ]);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->coreApplicationHttpClient->get('user', [
            'email' => CoreApplicationHttpClient::ROUTE_PARAMETER_USER_PLACEHOLDER,
        ]);

        $this->assertEquals('http://null/user/user@example.com/', $httpHistoryPlugin->getLastRequest()->getUrl());
    }

    /**
     * @return array
     */
    public function getRequestDoesNotThrowExceptionDataProvider()
    {
        return array_merge([
            'read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'user' => 'public',
                'options' => [],
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
            ],
            'invalid admin credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'user' => 'admin',
                'options' => [],
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
        return array_merge([
            'read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
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
        return array_merge([
            'read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
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
        return [
            '404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 404,
            ],
            '500' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 500,
            ],
            'curl exception' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'options' => [],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
            'invalid content type for expected json response' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'options' => [
                    CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                ],
                'expectedException' => InvalidContentTypeException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
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
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = $userService->getPublicUser();

        if ('private' === $userName) {
            $user = new User(self::USER_EMAIL);
        } elseif ('admin' === $userName) {
            $user = $userService->getAdminUser();
        }

        return $user;
    }
}