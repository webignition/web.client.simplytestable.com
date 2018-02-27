<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\PlanController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PlanControllerTest extends AbstractUserAccountControllerTest
{
    const ROUTE_NAME = 'user_account_plan_subscribe';

    const USER_EMAIL = 'user@example.com';
    const STRIPE_CARD_TOKEN = 'card_Bb4A2szGLfgwJe';

    /**
     * @var PlanController
     */
    protected $planController;

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

        $this->planController = $this->container->get(PlanController::class);
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

    public function testIndexActionPrivateUserPostRequest()
    {
        $userManager = $this->container->get(UserManager::class);
        $userManager->setUser(new User(self::USER_EMAIL));

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse($this->userData),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME),
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
     * @param Request $request
     * @param array $expectedFlashBagValues
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testSubscribeAction(
        array $httpFixtures,
        Request $request,
        array $expectedFlashBagValues
    ) {
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        /* @var RedirectResponse $response */
        $response = $this->planController->subscribeAction($request);

        $this->assertEquals('http://localhost/account/plan/', $response->getTargetUrl());
        $this->assertEquals($expectedFlashBagValues, $session->getFlashBag()->peekAll());
    }

    /**
     * @return array
     */
    public function subscribeActionDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'already on plan' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                ],
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
                'request' => new Request([], [
                    'plan' => 'personal',
                ]),
                'expectedFlashBagValues' => [],
            ],
            'HTTP 503 failure' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($this->userData),
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
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
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
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
}
