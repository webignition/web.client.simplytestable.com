<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\EntityEnclosingRequest;
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
     * @param string $userName
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    public function testGetThrowsException(
        array $httpFixtures,
        $userName,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->coreApplicationHttpClient->get('team_get');
    }

    /**
     * @dataProvider requestThrowsExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $userName
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    public function testPostThrowsException(
        array $httpFixtures,
        $userName,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->coreApplicationHttpClient->setUser($this->getUserFromUserName($userName));
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->coreApplicationHttpClient->post('team_get');
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
                'user' => 'public',
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            '404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
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
                'user' => 'public',
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
            'invalid user credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'user' => 'private',
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid user credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'user' => 'private',
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid admin credentials; 401' => [
                'httpFixtures' => [
                    HttpResponseFactory::createUnauthorisedResponse(),
                ],
                'user' => 'admin',
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid admin credentials; 403' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'user' => 'admin',
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    public function testGetSuccess()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse()
        ]);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response1 = $this->coreApplicationHttpClient->get('team_get');
        $response2 = $this->coreApplicationHttpClient->get('team_get');

        $this->assertEquals($response1, $response2);
    }

    public function testGetJsonDataInvalidContentType()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));

        $this->setExpectedException(InvalidContentTypeException::class);
        $this->coreApplicationHttpClient->getJsonData('team_get');
    }

    public function testPostGetJsonDataInvalidContentType()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));

        $this->setExpectedException(InvalidContentTypeException::class);
        $this->coreApplicationHttpClient->postGetJsonData('team_get');
    }

    /**
     * @dataProvider getJsonDataSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param mixed $expectedResponseData
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetJsonDataSuccess(array $httpFixtures, array $options, $expectedResponseData)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response1 = $this->coreApplicationHttpClient->getJsonData('team_get', [], $options);
        $response2 = $this->coreApplicationHttpClient->getJsonData('team_get', [], $options);

        $this->assertEquals($response1, $response2);
        $this->assertEquals($expectedResponseData, $response1);
    }

    /**
     * @dataProvider getJsonDataSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param array $options
     * @param mixed $expectedResponseData
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testPostGetJsonDataSuccess(array $httpFixtures, array $options, $expectedResponseData)
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $httpHistory = new HistoryPlugin();

        $this->coreApplicationHttpClient->getHttpClient()->addSubscriber($httpHistory);
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $response = $this->coreApplicationHttpClient->postGetJsonData('team_get', [], $postData, $options);

        $this->assertEquals($expectedResponseData, $response);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $httpHistory->getLastRequest();

        $this->assertEquals($postData, $lastRequest->getPostFields()->getAll());
    }

    /**
     * @return array
     */
    public function getJsonDataSuccessDataProvider()
    {
        $responseData = [
            'foo' => 'bar',
        ];

        return [
            '200 with data' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($responseData),
                ],
                'options' => [],
                'expectedResponseData' => $responseData,
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
                'expectedResponseData' => null,
            ],
        ];
    }

    public function testPostSuccess()
    {
        $postData = [
            'foo' => 'bar',
        ];

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $httpHistory = new HistoryPlugin();

        $this->coreApplicationHttpClient->getHttpClient()->addSubscriber($httpHistory);
        $this->coreApplicationHttpClient->setUser(new User(self::USER_EMAIL));
        $this->coreApplicationHttpClient->post('team_get', [], $postData);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $httpHistory->getLastRequest();

        $this->assertEquals($postData, $lastRequest->getPostFields()->getAll());
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
