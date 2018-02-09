<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller;

use SimplyTestable\WebClientBundle\Controller\UserAccountPlanController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserAccountPlanControllerTest extends AbstractBaseTestCase
{
    const ROUTE_NAME = 'user_account_plan_subscribe';

    const USER_EMAIL = 'user@example.com';
    const STRIPE_CARD_TOKEN = 'card_Bb4A2szGLfgwJe';

    /**
     * @var UserAccountPlanController
     */
    protected $userAccountPlanController;

    /**
     * @var array
     */
    private $userData = [
        'email' => self::USER_EMAIL,
        'user_plan' => [
            'plan' => [
                'name' => 'agency',
                'is_premium' => true,
            ],
            'start_trial_period' => 30,
        ],
        'plan_constraints' => [
            'urls_per_job' => 10,
        ],
        'team_summary' => [
            'in' => false,
            'has_invite' => false,
        ],
        'stripe_customer' => [
            'id' => 'cus_aaaaaaaaaaaaa0',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userAccountPlanController = new UserAccountPlanController();
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

    public function testIndexActionPrivateUserPostRequest()
    {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->client->getCookieJar()->set(new Cookie(
            UserService::USER_COOKIE_KEY,
            $userSerializerService->serializeToString(new User(self::USER_EMAIL))
        ));

        $this->setHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->userData),
        ]);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(),
            [
                'plan' => 'personal',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/plan/', $response->getTargetUrl());
    }

    /**
     * @dataProvider subscribeActionDataProvider
     *
     * @param array $httpFixtures
     * @param array $coreApplicationHttpClientFixtures
     * @param Request $request
     * @param array $expectedFlashBagValues
     *
     * @throws WebResourceException
     * @throws \Exception
     */
    public function testSubscribeAction(
        array $httpFixtures,
        array $coreApplicationHttpClientFixtures,
        Request $request,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

        $user = new User(self::USER_EMAIL);
        $userService->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setHttpFixtures($httpFixtures);
        $this->setCoreApplicationHttpClientHttpFixtures($coreApplicationHttpClientFixtures);

        $this->userAccountPlanController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->userAccountPlanController->subscribeAction($request);

        $this->assertEquals('http://localhost/account/plan/', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function subscribeActionDataProvider()
    {
        return [
            'already on plan' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
                'coreApplicationHttpClientFixtures' => [],
                'request' => new Request([], [
                    'plan' => 'agency',
                ]),
                'expectedFlashBagValues' => [
                    'plan_subscribe_success' => [
                        'already-on-plan',
                    ],
                ],
            ],
            'in team, not leader' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => 'leader@example.com',
                            'name' => 'Team Name',
                        ],
                        'members' => [
                            self::USER_EMAIL,
                        ],
                    ]),
                ],
                'coreApplicationHttpClientFixtures' => [],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [],
            ],
            'HTTP 503 failure' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
                'coreApplicationHttpClientFixtures' => [
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                    HttpResponseFactory::createServiceUnavailableResponse(),
                ],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [
                    'plan_subscribe_error' => [
                        503,
                    ],
                ],
            ],
            'CURL 28 failure' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
                'coreApplicationHttpClientFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [
                    'plan_subscribe_error' => [
                        28,
                    ],
                ],
            ],
            'stripe card error' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
                'coreApplicationHttpClientFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-Stripe-Error-Message' => 'The zip code you supplied failed validation.',
                        'X-Stripe-Error-Param' => 'address_zip',
                        'X-Stripe-Error-Code' => 'incorrect_zip',
                    ]),
                ],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [
                    'user_account_card_exception_message' => [
                        'The zip code you supplied failed validation.'
                    ],
                    'user_account_card_exception_param' => [
                        'address_zip'
                    ],
                    'user_account_card_exception_code' => [
                        'incorrect_zip'
                    ],
                ],
            ],
            'success, not in team' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
                'coreApplicationHttpClientFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [
                    'plan_subscribe_success' => [
                        'ok',
                    ],
                ],
            ],
            'success, in team, is leader' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => self::USER_EMAIL,
                            'name' => 'Team Name',
                        ],
                        'members' => [
                            self::USER_EMAIL,
                        ],
                    ]),
                ],
                'coreApplicationHttpClientFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [
                    'plan_subscribe_success' => [
                        'ok',
                    ],
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

        return $router->generate(self::ROUTE_NAME);
    }
}
