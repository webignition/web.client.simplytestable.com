<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

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

        $this->userEmailChangeRequestService = $this->container->get(
            'simplytestable.services.useremailchangerequestservice'
        );

        $this->user = new User(self::USER_EMAIL);
    }

    /**
     * @dataProvider getEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedEmailChangeRequest
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testGetEmailChangeRequest(array $httpFixtures, $expectedEmailChangeRequest)
    {
        $this->setHttpFixtures($httpFixtures);

        $emailChangeRequest = $this->userEmailChangeRequestService->getEmailChangeRequest(
            $this->user->getUsername()
        );

        $this->assertEquals($expectedEmailChangeRequest, $emailChangeRequest);
        $this->assertEquals(
            'http://null/user/user@example.com/email-change-request/',
            $this->getLastRequest()->getUrl()
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
                    HttpResponseFactory::create(404),
                ],
                'expectedEmailChangeRequest' => null,
            ],
            'found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($emailChangeRequestValues),
                ],
                'expectedEmailChangeRequest' => $emailChangeRequestValues,
            ],
        ];
    }

    /**
     * @dataProvider hasEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedHasEmailChangeRequest
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testHasEmailChangeRequest(array $httpFixtures, $expectedHasEmailChangeRequest)
    {
        $this->setHttpFixtures($httpFixtures);

        $hasEmailChangeRequest = $this->userEmailChangeRequestService->hasEmailChangeRequest(
            $this->user->getUsername()
        );

        $this->assertEquals($expectedHasEmailChangeRequest, $hasEmailChangeRequest);
        $this->assertEquals(
            'http://null/user/user@example.com/email-change-request/',
            $this->getLastRequest()->getUrl()
        );
    }

    /**
     * @return array
     */
    public function hasEmailChangeRequestDataProvider()
    {
        return [
            'not found' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(404),
                ],
                'expectedHasEmailChangeRequest' => false,
            ],
            'found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'new_email' => 'foo@example.com',
                        'token' => 'token-value',
                    ]),
                ],
                'expectedHasEmailChangeRequest' => true,
            ],
        ];
    }

    /**
     * @dataProvider modifyEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param mixed $expectedReturnValue
     * @param bool $expectedRequestIsMade
     */
    public function testCancelEmailChangeRequest(array $httpFixtures, $expectedReturnValue, $expectedRequestIsMade)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->userEmailChangeRequestService->setUser($this->user);
        $returnValue = $this->userEmailChangeRequestService->cancelEmailChangeRequest();

        $this->assertEquals($expectedReturnValue, $returnValue);

        $lastRequest = $this->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/cancel/',
                $lastRequest->getUrl()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @dataProvider modifyEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param mixed $expectedReturnValue
     * @param bool $expectedRequestIsMade
     */
    public function testConfirmEmailChangeRequest(array $httpFixtures, $expectedReturnValue, $expectedRequestIsMade)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->userEmailChangeRequestService->setUser($this->user);
        $returnValue = $this->userEmailChangeRequestService->confirmEmailChangeRequest('token-value');

        $this->assertEquals($expectedReturnValue, $returnValue);

        $lastRequest = $this->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/token-value/',
                $lastRequest->getUrl()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @dataProvider modifyEmailChangeRequestDataProvider
     *
     * @param array $httpFixtures
     * @param mixed $expectedReturnValue
     * @param bool $expectedRequestIsMade
     */
    public function testCreateEmailChangeRequest(array $httpFixtures, $expectedReturnValue, $expectedRequestIsMade)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->userEmailChangeRequestService->setUser($this->user);
        $returnValue = $this->userEmailChangeRequestService->createEmailChangeRequest('foo@example.com');

        $this->assertEquals($expectedReturnValue, $returnValue);

        $lastRequest = $this->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/email-change-request/foo@example.com/create/',
                $lastRequest->getUrl()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @return array
     */
    public function modifyEmailChangeRequestDataProvider()
    {
        return [
            'HTTP 503' => [
                'httpFixture' => [
                    HttpResponseFactory::create(503),
                ],
                'expectedReturnValue' => 503,
                'expectedRequestIsMade' => true,
            ],
            'CURL 28' => [
                'httpFixture' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedReturnValue' => 28,
                'expectedRequestIsMade' => false,
            ],
            'Success' => [
                'httpFixture' => [
                    HttpResponseFactory::create(200),
                ],
                'expectedReturnValue' => true,
                'expectedRequestIsMade' => true,
            ],
        ];
    }
}
