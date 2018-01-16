<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\Coupon;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserAccountCardService;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class UserAccountCardServiceTest extends AbstractCoreApplicationServiceTest
{
    const STRIPE_CARD_TOKEN = 'tok_Bb4A2szGLfgwJe';

    /**
     * @var UserAccountCardService
     */
    private $userAccountCardService;

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

        $this->userAccountCardService = $this->container->get(
            'simplytestable.services.useraccountcardservice'
        );

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider associateStripeErrorDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionParam
     * @param string $expectedExceptionStripeCode
     */
    public function testAssociateStripeError(
        array $httpFixtures,
        $expectedExceptionParam,
        $expectedExceptionStripeCode
    ) {
        $this->setHttpFixtures($httpFixtures);

        $this->userAccountCardService->setUser($this->user);

        try {
            $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
            $this->fail(UserAccountCardException::class. ' not thrown');
        } catch (UserAccountCardException $userAccountCardException) {
            $this->assertEquals($expectedExceptionParam, $userAccountCardException->getParam());
            $this->assertEquals($expectedExceptionStripeCode, $userAccountCardException->getStripeCode());
        }
    }

    /**
     * @return array
     */
    public function associateStripeErrorDataProvider()
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
     * @dataProvider associateDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedReturnValue
     * @param bool $expectedRequestIsMade
     *
     * @throws UserAccountCardException
     */
    public function testAssociate(array $httpFixtures, $expectedReturnValue, $expectedRequestIsMade)
    {
        $this->setHttpFixtures($httpFixtures);

        $returnValue = $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);

        $this->assertEquals($expectedReturnValue, $returnValue);

        $lastRequest = $this->getLastRequest();

        if ($expectedRequestIsMade) {
            $this->assertEquals(
                'http://null/user/user@example.com/card/tok_Bb4A2szGLfgwJe/associate/',
                $lastRequest->getUrl()
            );
        } else {
            $this->assertNull($lastRequest);
        }
    }

    /**
     * @return array
     */
    public function associateDataProvider()
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
