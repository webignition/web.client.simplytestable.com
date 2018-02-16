<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\View\User\Account\IndexController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use webignition\Model\Stripe\Invoice\Invoice;

class IndexControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/Account/Index:index.html.twig';
    const ROUTE_NAME = 'view_user_account_index_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var IndexController
     */
    private $indexController;

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
            'subscription' => [
                'plan' => [
                    'currency' => 'gbp',
                ],
            ],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->indexController = new IndexController();
    }

    /**
     * @dataProvider indexActionInvalidGetRequestDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedRedirectUrl
     */
    public function testIndexActionInvalidGetRequest(array $httpFixtures, $expectedRedirectUrl)
    {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($expectedRedirectUrl, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function indexActionInvalidGetRequestDataProvider()
    {
        return [
            'invalid user' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(404),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::create(200),
                ],
                'expectedRedirectUrl' =>
                    'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D'
            ],
        ];
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $user = new User(self::USER_EMAIL);
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::create(200),
            HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                'team_summary' => [
                    'in' => false,
                    'has_invite' => false,
                ],
            ])),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createJsonResponse([]),
            HttpResponseFactory::createNotFoundResponse(),
        ]);

        $this->client->getCookieJar()->set(new Cookie(
            UserManager::USER_COOKIE_KEY,
            $userSerializerService->serializeToString($user)
        ));

        $this->client->request(
            'GET',
            $this->createRequestUrl()
        );

        /* @var Response $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }


    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param array $flashBagValues
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        array $flashBagValues,
        EngineInterface $templatingEngine
    ) {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $session = $this->container->get('session');

        $user = new User(self::USER_EMAIL);

        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        if (!empty($flashBagValues)) {
            foreach ($flashBagValues as $key => $value) {
                $session->getFlashBag()->set($key, $value);
            }
        }

        $containerFactory = new ContainerFactory($this->container);
        $container = $containerFactory->create(
            [
                'simplytestable.services.userservice',
                'simplytestable.services.mailchimp.listrecipients',
                'simplytestable.services.teamservice',
                'simplytestable.services.useremailchangerequestservice',
                'simplytestable.services.userstripeeventservice',
                'simplytestable.services.flashbagvalues',
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'stripe',
                'currency_map',
            ]
        );

        $this->indexController->setContainer($container);

        $response = $this->indexController->indexAction(new Request());
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'no stripe events, no stripe customer, not in a team, no email change request, no flash values' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'email' => self::USER_EMAIL,
                        'user_plan' => [
                            'plan' => [
                                'name' => 'agency-custom',
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
                    ]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'no stripe events, no stripe subscription' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                        'stripe_customer' => null,
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'no stripe events, has stripe subscription, has active card' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                        'stripe_customer' => [
                            'id' => 'cus_aaaaaaaaaaaaa0',
                            'subscription' => [
                                'plan' => [
                                    'currency' => 'gbp',
                                ],
                            ],
                            'active_card' => [
                                'id' => 'card_Bb4A2szGLfgwJe',
                                'exp_month' => 12,
                                'exp_year' => 2020,
                            ],
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEquals('December', $parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has stripe invoice.updated event' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'stripe_id' => 'evt_C84zMHQhwuWUxN',
                            'type' => 'invoice.updated',
                            'stripe_event_data' => json_encode([
                                'id' => 'evt_C84zMHQhwuWUxN',
                                'type' => 'invoice.updated',
                                'object' => 'event',
                                'data' => [
                                    'object' => [
                                        'id' => 'in_C841klxCsOp9Uj',
                                        'object' => 'invoice',
                                    ],
                                ],
                            ]),
                        ],
                    ]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertInternalType('array', $parameters['stripe_event_data']);
                            $stripeEventData = $parameters['stripe_event_data'];

                            $this->assertArrayHasKey('invoice', $stripeEventData);
                            $this->assertInstanceOf(Invoice::class, $stripeEventData['invoice']);

                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has stripe invoice.created event' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([
                        [
                            'stripe_id' => 'evt_C84zMHQhwuWUxN',
                            'type' => 'invoice.created',
                            'stripe_event_data' => json_encode([
                                'id' => 'evt_C84zMHQhwuWUxN',
                                'type' => 'invoice.updated',
                                'object' => 'event',
                                'data' => [
                                    'object' => [
                                        'id' => 'in_C841klxCsOp9Uj',
                                        'object' => 'invoice',
                                    ],
                                ],
                            ]),
                        ],
                    ]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertInternalType('array', $parameters['stripe_event_data']);
                            $stripeEventData = $parameters['stripe_event_data'];

                            $this->assertArrayHasKey('invoice', $stripeEventData);
                            $this->assertInstanceOf(Invoice::class, $stripeEventData['invoice']);

                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'is in a team' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => 'leader@example.com',
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has email change request' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([
                        'new_email' => 'foo@example.com',
                        'token' => 'token-value',
                    ]),
                ],
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);

                            $this->assertArrayHasKey('email_change_request', $parameters);
                            $this->assertEquals(
                                [
                                    'new_email' => 'foo@example.com',
                                    'token' => 'token-value',
                                ],
                                $parameters['email_change_request']
                            );

                            $this->assertArrayHasKey('token', $parameters);
                            $this->assertNull($parameters['token']);

                            $this->assertArrayNotHasKey('user_account_details_update_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_update_email', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_confirm_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_card_exception_message', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_param', $parameters);
                            $this->assertArrayNotHasKey('user_account_card_exception_code', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_notice', $parameters);
                            $this->assertArrayNotHasKey('user_account_details_resend_email_change_error', $parameters);
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_email_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                'user_account_details_update_password_request_notice',
                                $parameters
                            );
                            $this->assertArrayNotHasKey('user_account_newssubscriptions_update', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'has flash values' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createJsonResponse([]),
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'flashBagValues' => [
                    'user_account_details_update_notice' => 'foo1',
                    'user_account_details_update_email' => 'foo2',
                    'user_account_details_update_email_confirm_notice' => 'foo3',
                    'user_account_card_exception_message' => 'foo4',
                    'user_account_card_exception_param' => 'foo5',
                    'user_account_card_exception_code' => 'foo6',
                    'user_account_details_resend_email_change_notice' => 'foo7',
                    'user_account_details_resend_email_change_error' => 'foo8',
                    'user_account_details_update_email_request_notice' => 'foo9',
                    'user_account_details_update_password_request_notice' => 'foo10',
                    'user_account_newssubscriptions_update' => 'foo11',
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertEmpty($parameters['stripe_event_data']);
                            $this->assertFalse($parameters['mailchimp_updates_subscribed']);
                            $this->assertFalse($parameters['mailchimp_announcements_subscribed']);
                            $this->assertEmpty($parameters['card_expiry_month']);
                            $this->assertEquals('Agency', $parameters['plan_presentation_name']);

                            $this->assertArrayNotHasKey('team', $parameters);
                            $this->assertArrayNotHasKey('email_change_request', $parameters);
                            $this->assertArrayNotHasKey('token', $parameters);

                            $this->assertEquals('foo1', $parameters['user_account_details_update_notice']);
                            $this->assertEquals('foo2', $parameters['user_account_details_update_email']);
                            $this->assertEquals(
                                'foo3',
                                $parameters['user_account_details_update_email_confirm_notice']
                            );
                            $this->assertEquals('foo4', $parameters['user_account_card_exception_message']);
                            $this->assertEquals('foo5', $parameters['user_account_card_exception_param']);
                            $this->assertEquals('foo6', $parameters['user_account_card_exception_code']);
                            $this->assertEquals('foo7', $parameters['user_account_details_resend_email_change_notice']);
                            $this->assertEquals('foo8', $parameters['user_account_details_resend_email_change_error']);
                            $this->assertEquals(
                                'foo9',
                                $parameters['user_account_details_update_email_request_notice']
                            );
                            $this->assertEquals(
                                'foo10',
                                $parameters['user_account_details_update_password_request_notice']
                            );
                            $this->assertEquals('foo11', $parameters['user_account_newssubscriptions_update']);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @param string $viewName
     * @param array $parameters
     */
    private function assertCommonViewData($viewName, $parameters)
    {
        $this->assertEquals(self::VIEW_NAME, $viewName);
        $this->assertViewParameterKeys($parameters);

        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
        $this->assertInternalType('array', $parameters['stripe']);
        $this->assertInternalType('array', $parameters['currency_map']);
    }

    /**
     * @param array $parameters
     */
    private function assertViewParameterKeys(array $parameters)
    {
        $this->assertArraySubset(
            [
                'user',
                'is_logged_in',
                'user_summary',
                'plan_presentation_name',
                'stripe_event_data',
                'stripe',
                'mailchimp_updates_subscribed',
                'mailchimp_announcements_subscribed',
                'card_expiry_month',
                'currency_map',
            ],
            array_keys($parameters)
        );
    }

    /**
     * @return string
     */
    private function createRequestUrl()
    {
        $router = $this->container->get('router');

        return $router->generate(self::ROUTE_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }
}
