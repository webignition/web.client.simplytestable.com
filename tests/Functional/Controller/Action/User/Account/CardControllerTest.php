<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\Action\User\Account;

use App\Controller\Action\User\Account\CardController;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use webignition\SimplyTestableUserModel\User;

class CardControllerTest extends AbstractUserAccountControllerTest
{
    const ROUTE_NAME = 'action_user_account_card_associate';
    const USER_EMAIL = 'user@example.com';
    const STRIPE_CARD_TOKEN = 'card_Bb4A2szGLfgwJe';

    /**
     * @var CardController
     */
    protected $userAccountCardController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userAccountCardController = self::$container->get(CardController::class);
    }

    /**
     * {@inheritdoc}
     */
    public function postRequestPublicUserDataProvider()
    {
        return [
            'default' => [
                'routeName' => self::ROUTE_NAME,
            ],
        ];
    }

    /**
     * @dataProvider associateActionPrivateUserPostRequestDataProvider
     */
    public function testAssociateActionPrivateUserPostRequest(array $httpFixtures, array $expectedResponseData)
    {
        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser(new User(self::USER_EMAIL));

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME, [
                'stripe_card_token' => self::STRIPE_CARD_TOKEN,
            ])
        );

        /* @var JsonResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals($expectedResponseData, $responseData);
    }

    public function associateActionPrivateUserPostRequestDataProvider(): array
    {
        return [
            'success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedResponseData' => [
                    'this_url' => 'http://localhost/account/',
                ],
            ],
            'stripe error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                    HttpResponseFactory::createBadRequestResponse([
                        'X-Stripe-Error-Message' => 'The zip code you supplied failed validation.',
                        'X-Stripe-Error-Param' => 'address_zip',
                        'X-Stripe-Error-Code' => 'incorrect_zip',
                    ]),
                ],
                'expectedResponseData' => [
                    'user_account_card_exception_message' => 'The zip code you supplied failed validation.',
                    'user_account_card_exception_param' => 'address_zip',
                    'user_account_card_exception_code' => 'incorrect_zip',
                ],
            ],
        ];
    }
}
