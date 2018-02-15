<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserAccountCardService;
use SimplyTestable\WebClientBundle\Tests\Factory\ConnectExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

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
        $this->coreApplicationHttpClient->setUser($this->user);
    }

    /**
     * @dataProvider associateStripeErrorDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionParam
     * @param string $expectedExceptionStripeCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testAssociateStripeError(
        array $httpFixtures,
        $expectedExceptionParam,
        $expectedExceptionStripeCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

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
     * @dataProvider associateFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws UserAccountCardException
     */
    public function testAssociateFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
    }

    /**
     * @return array
     */
    public function associateFailureDataProvider()
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

    public function testAssociateSuccess()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);

        $this->assertEquals(
            'http://null/user/user@example.com/card/tok_Bb4A2szGLfgwJe/associate/',
            $this->getLastRequest()->getUrl()
        );
    }
}
