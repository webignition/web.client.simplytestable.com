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
     * @dataProvider requestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $userName
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetThrowsException(
        array $httpFixtures,
        array $options,
        $userName,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->coreApplicationHttpClient->get('team_get', [], $options);
    }

    /**
     * @dataProvider requestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param string $userName
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testPostThrowsException(
        array $httpFixtures,
        array $options,
        $userName,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->coreApplicationHttpClient->post('team_get', [], [], $options);
    }

    /**
     * @return array
     */
    public function requestThrowsExceptionDataProvider()
    {
        return [
            'read only' => [
                'httpFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'options' => [],
                'user' => 'public',
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            '404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'options' => [],
                'user' => 'public',
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
                'user' => 'public',
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
                'user' => 'public',
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
            'invalid user credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'options' => [],
                'user' => 'private',
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid user credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'options' => [],
                'user' => 'private',
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid admin credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'options' => [],
                'user' => 'admin',
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid admin credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'options' => [],
                'user' => 'admin',
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid content type for expected json response' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'options' => [
                    CoreApplicationHttpClient::OPT_EXPECT_JSON_RESPONSE => true,
                ],
                'user' => 'public',
                'expectedException' => InvalidContentTypeException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ];
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
