<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\UserAccountCardException;
use App\Services\UserPlanSubscriptionService;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use webignition\SimplyTestableUserModel\User;

class UserPlanSubscriptionServiceTest extends AbstractCoreApplicationServiceTest
{
    const PLAN = 'personal';

    /**
     * @var UserPlanSubscriptionService
     */
    private $userPlanSubscriptionService;

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

        $this->userPlanSubscriptionService = self::$container->get(UserPlanSubscriptionService::class);

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider subscribeStripeErrorDataProvider
     */
    public function testSubscribeStripeError(
        array $httpFixtures,
        string $expectedExceptionParam,
        string $expectedExceptionStripeCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        try {
            $this->userPlanSubscriptionService->subscribe($this->user, self::PLAN);
            $this->fail(UserAccountCardException::class . ' not thrown');
        } catch (UserAccountCardException $userAccountCardException) {
            $this->assertEquals($expectedExceptionParam, $userAccountCardException->getParam());
            $this->assertEquals($expectedExceptionStripeCode, $userAccountCardException->getStripeCode());
        }
    }

    public function subscribeStripeErrorDataProvider(): array
    {
        return [
            'invalid address zip' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-Stripe-Error-Message' => 'The zip code you supplied failed validation.',
                        'X-Stripe-Error-Param' => 'address_zip',
                        'X-Stripe-Error-Code' => 'incorrect_zip'
                    ]),
                ],
                'expectedExceptionParam' => 'address_zip',
                'expectedExceptionStripeCode' => 'incorrect_zip',
            ],
        ];
    }

    /**
     * @dataProvider subscribeFailureDataProvider
     */
    public function testSubscribeFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userPlanSubscriptionService->subscribe($this->user, self::PLAN);
    }

    public function subscribeFailureDataProvider(): array
    {
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 400' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Bad Request',
                'expectedExceptionCode' => 400,
            ],
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testSubscribeSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userPlanSubscriptionService->subscribe($this->user, self::PLAN);

        $this->assertEquals(
            'http://null/user/user@example.com/personal/subscribe/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
