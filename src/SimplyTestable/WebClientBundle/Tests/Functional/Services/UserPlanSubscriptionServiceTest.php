<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserPlanSubscriptionService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

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

        $this->userPlanSubscriptionService = $this->container->get(
            'simplytestable.services.userplansubscriptionservice'
        );

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider subscribeStripeErrorDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionParam
     * @param string $expectedExceptionStripeCode
     */
    public function testSubscribeStripeError(
        array $httpFixtures,
        $expectedExceptionParam,
        $expectedExceptionStripeCode
    ) {
        $this->setHttpFixtures($httpFixtures);

        $this->userPlanSubscriptionService->setUser($this->user);

        try {
            $this->userPlanSubscriptionService->subscribe($this->user, self::PLAN);
            $this->fail(UserAccountCardException::class. ' not thrown');
        } catch (UserAccountCardException $userAccountCardException) {
            $this->assertEquals($expectedExceptionParam, $userAccountCardException->getParam());
            $this->assertEquals($expectedExceptionStripeCode, $userAccountCardException->getStripeCode());
        }
    }

    /**
     * @return array
     */
    public function subscribeStripeErrorDataProvider()
    {
        return [
            'invalid address zip' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(
                        400,
                        [
                            'X-Stripe-Error-Message' => 'The zip code you supplied failed validation.',
                            'X-Stripe-Error-Param' => 'address_zip',
                            'X-Stripe-Error-Code' => 'incorrect_zip'
                        ]
                    ),
                ],
                'expectedExceptionParam' => 'address_zip',
                'expectedExceptionStripeCode' => 'incorrect_zip',
            ],
        ];
    }

    /**
     * @dataProvider subscribeDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedReturnValue
     * @param bool $expectedRequestIsMade
     *
     * @throws UserAccountCardException
     */
    public function testSubscribe(array $httpFixtures, $expectedReturnValue, $expectedRequestIsMade)
    {
        $this->setHttpFixtures($httpFixtures);

        $returnValue = $this->userPlanSubscriptionService->subscribe($this->user, self::PLAN);

        $this->assertEquals($expectedReturnValue, $returnValue);

        $lastRequest = $this->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/personal/subscribe/',
                $lastRequest->getUrl()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @return array
     */
    public function subscribeDataProvider()
    {
        return [
            'HTTP 400' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(400),
                ],
                'expectedReturnValue' => 400,
                'expectedRequestIsMade' => true,
            ],
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(404),
                ],
                'expectedReturnValue' => 404,
                'expectedRequestIsMade' => true,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedReturnValue' => 28,
                'expectedRequestIsMade' => false,
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                ],
                'expectedReturnValue' => true,
                'expectedRequestIsMade' => true,
            ],
        ];
    }
}
