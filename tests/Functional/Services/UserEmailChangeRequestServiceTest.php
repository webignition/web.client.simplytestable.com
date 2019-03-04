<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Exception\InvalidCredentialsException;
use App\Exception\UserEmailChangeException;
use App\Services\UserEmailChangeRequestService;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
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
     */
    public function testGetEmailChangeRequest(array $httpFixtures, ?array $expectedEmailChangeRequest)
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

    public function getEmailChangeRequestDataProvider(): array
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
     */
    public function testCancelEmailChangeRequest(array $httpFixtures, bool $expectedRequestIsMade)
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
     */
    public function testConfirmEmailChangeRequestUserEmailChangeException(
        array $httpFixtures,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
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
     */
    public function testConfirmEmailChangeRequest(array $httpFixtures, bool $expectedRequestIsMade)
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
     */
    public function testCreateEmailChangeRequestUserEmailChangeException(
        array $httpFixtures,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
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
     */
    public function testCreateEmailChangeRequestSuccess(array $httpFixtures, bool $expectedRequestIsMade)
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

    public function modifyEmailChangeRequestSuccessDataProvider(): array
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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

    public function modifyEmailChangeRequestUserEmailChangeExceptionDataProvider(): array
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
