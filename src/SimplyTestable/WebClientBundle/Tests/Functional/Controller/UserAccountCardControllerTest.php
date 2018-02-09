<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\UserAccountCardController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserAccountCardControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'user_account_card_associate';

    const USER_EMAIL = 'user@example.com';
    const STRIPE_CARD_TOKEN = 'card_Bb4A2szGLfgwJe';

    /**
     * @var UserAccountCardController
     */
    protected $userAccountCardController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userAccountCardController = new UserAccountCardController();
    }

    public function testIndexActionPublicUserPostRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl()
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/signin/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionPrivateUserPostRequestDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedResponseData
     */
    public function testIndexActionPrivateUserPostRequest(array $httpFixtures, array $expectedResponseData)
    {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([$httpFixtures[0]]);
        $this->setCoreApplicationHttpClientHttpFixtures([$httpFixtures[1]]);

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->client->request(
            'POST',
            $this->createRequestUrl()
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

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME, [
            'stripe_card_token' => self::STRIPE_CARD_TOKEN,
        ]);
    }
}
