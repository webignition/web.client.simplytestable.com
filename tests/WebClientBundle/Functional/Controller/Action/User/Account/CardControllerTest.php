<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\CardController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;

class CardControllerTest extends AbstractUserAccountControllerTest
{
    const ROUTE_NAME = 'user_account_card_associate';

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

        $this->userAccountCardController = $this->container->get(CardController::class);
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
     * @dataProvider indexActionPrivateUserPostRequestDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedResponseData
     */
    public function testIndexActionPrivateUserPostRequest(array $httpFixtures, array $expectedResponseData)
    {
        $userManager = $this->container->get(UserManager::class);
        $userManager->setUser(new User(self::USER_EMAIL));

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);
        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME, [
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

    /**
     * @return array
     */
    public function indexActionPrivateUserPostRequestDataProvider()
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
