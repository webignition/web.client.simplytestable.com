<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Exception\CoreApplicationRequestException;
use App\Exception\UserAccountCardException;
use App\Services\UserAccountCardService;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
use webignition\SimplyTestableUserModel\User;

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

        $this->userAccountCardService = self::$container->get(UserAccountCardService::class);

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider associateStripeErrorDataProvider
     */
    public function testAssociateStripeError(
        array $httpFixtures,
        string $expectedExceptionParam,
        string $expectedExceptionStripeCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        try {
            $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
            $this->fail(UserAccountCardException::class. ' not thrown');
        } catch (UserAccountCardException $userAccountCardException) {
            $this->assertEquals($expectedExceptionParam, $userAccountCardException->getParam());
            $this->assertEquals($expectedExceptionStripeCode, $userAccountCardException->getStripeCode());
        }
    }

    public function associateStripeErrorDataProvider(): array
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
     */
    public function testAssociateFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
    }

    public function associateFailureDataProvider(): array
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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);

        $this->assertEquals(
            'http://null/user/user@example.com/card/tok_Bb4A2szGLfgwJe/associate/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
