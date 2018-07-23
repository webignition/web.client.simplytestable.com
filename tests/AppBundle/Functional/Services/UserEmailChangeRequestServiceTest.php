<?php

namespace Tests\AppBundle\Functional\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserEmailChangeException;
use App\Services\UserEmailChangeRequestService;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;
use webignition\SimplyTestableUserModel\User;

class UserEmailChangeRequestServiceTest extends AbstractCoreApplicationServiceTest
{
    const USER_EMAIL = 'user@example.com';

    /**
     * @var UserEmailChangeRequestService
     */
    private $userEmailChangeRequestService;

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userEmailChangeRequestService = self::$container->get(UserEmailChangeRequestService::class);

        $this->user = new User(self::USER_EMAIL);
    }

    /**
     * @dataProvider getEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param array|null $expectedEmailChangeRequest
     *
     * @throws InvalidAdminCredentialsException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    public function testGetEmailChangeRequest(array $httpFixtures, $expectedEmailChangeRequest)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $emailChangeRequest = $this->userEmailChangeRequestService->getEmailChangeRequest(
            $this->user->getUsername()
        );

        $this->assertEquals($expectedEmailChangeRequest, $emailChangeRequest);
        $this->assertEquals(
            'http://null/user/user@example.com/email-change-request/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    /**
     * @return array
     */
    public function getEmailChangeRequestDataProvider()
    {
        $emailChangeRequestValues = [
            'new_email' => 'foo@example.com',
            'token' => 'token-value',
        ];

        return [
            'not found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedEmailChangeRequest' => null,
            ],
            'non-empty' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($emailChangeRequestValues),
                ],
                'expectedEmailChangeRequest' => $emailChangeRequestValues,
            ],
        ];
    }

    /**
     * @dataProvider modifyEmailChangeRequestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedRequestIsMade
     *
     * @throws InvalidCredentialsException
     */
    public function testCancelEmailChangeRequest(array $httpFixtures, $expectedRequestIsMade)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->userManager->setUser($this->user);
        $this->userEmailChangeRequestService->cancelEmailChangeRequest();

        $lastRequest = $this->httpHistory->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/cancel/',
                $lastRequest->getUri()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @dataProvider modifyEmailChangeRequestUserEmailChangeExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function testConfirmEmailChangeRequestUserEmailChangeException(
        array $httpFixtures,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException(UserEmailChangeException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userManager->setUser($this->user);
        $this->userEmailChangeRequestService->confirmEmailChangeRequest([
            'new_email' => 'foo@example.com',
            'token' => 'foo',
        ]);
    }

    /**
     * @dataProvider modifyEmailChangeRequestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedRequestIsMade
     *
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function testConfirmEmailChangeRequest(array $httpFixtures, $expectedRequestIsMade)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->userManager->setUser($this->user);
        $this->userEmailChangeRequestService->confirmEmailChangeRequest([
            'new_email' => 'foo@example.com',
            'token' => 'token-value',
        ]);

        $lastRequest = $this->httpHistory->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/token-value/',
                $lastRequest->getUri()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @dataProvider modifyEmailChangeRequestUserEmailChangeExceptionDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function testCreateEmailChangeRequestUserEmailChangeException(
        array $httpFixtures,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException(UserEmailChangeException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userManager->setUser($this->user);
        $this->userEmailChangeRequestService->createEmailChangeRequest('foo@example.com');
    }

    /**
     * @dataProvider modifyEmailChangeRequestSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param $expectedRequestIsMade
     *
     * @throws InvalidCredentialsException
     * @throws UserEmailChangeException
     */
    public function testCreateEmailChangeRequestSuccess(array $httpFixtures, $expectedRequestIsMade)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->userManager->setUser($this->user);
        $this->userEmailChangeRequestService->createEmailChangeRequest('foo@example.com');

        $lastRequest = $this->httpHistory->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/foo@example.com/create/',
                $lastRequest->getUri()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @return array
     */
    public function modifyEmailChangeRequestSuccessDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 503' => [
                'httpFixture' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedRequestIsMade' => true,
            ],
            'CURL 28' => [
                'httpFixture' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'expectedRequestIsMade' => true,
            ],
            'Success' => [
                'httpFixture' => [
                    HttpResponseFactory::create(200),
                ],
                'expectedRequestIsMade' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function modifyEmailChangeRequestUserEmailChangeExceptionDataProvider()
    {
        return [
            'email address taken' => [
                'httpFixtures' => [
                    HttpResponseFactory::createConflictResponse(),
                ],
                'expectedExceptionMessage' => 'Email address foo@example.com already taken',
                'expectedExceptionCode' => UserEmailChangeException::CODE_EMAIL_ALREADY_TAKEN,
            ],
            'unknown' => [
                'httpFixtures' => [
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedExceptionMessage' => UserEmailChangeException::MESSAGE_UNKNOWN,
                'expectedExceptionCode' => UserEmailChangeException::CODE_UNKNOWN,
            ],
        ];
    }
}
