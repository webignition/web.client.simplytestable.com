<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\Account;

use SimplyTestable\WebClientBundle\Controller\View\User\Account\CardController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Model\User\Summary as UserSummary;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\TeamService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Tests\WebClientBundle\Factory\ContainerFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CardControllerTest extends AbstractBaseTestCase
{
    const VIEW_NAME = 'SimplyTestableWebClientBundle:bs3/User/Account/Card:index.html.twig';
    const ROUTE_NAME = 'view_user_account_card_index';

    const USER_EMAIL = 'user@example.com';

    /**
     * @var CardController
     */
    private $cardController;

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
            'in' => true,
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

        $this->cardController = new CardController();
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
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRedirectUrl' => 'http://localhost/signout/',
            ],
            'public user' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse()
                ],
                'expectedRedirectUrl' =>
                    'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2NhcmRfaW5kZXgifQ%3D%3D'
            ],
        ];
    }

    public function testIndexActionPrivateUserGetRequest()
    {
        $user = new User(self::USER_EMAIL);
        $userSerializerService = $this->container->get(UserSerializerService::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                'team_summary' => [
                    'in' => false,
                    'has_invite' => false,
                ],
            ])),
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

    public function testIndexActionInTeamNotLeader()
    {
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

        $user = new User(self::USER_EMAIL);
        $userManager->setUser($user);
        $coreApplicationHttpClient->setUser($user);

        $this->setCoreApplicationHttpClientHttpFixtures([
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
        ]);

        $this->cardController->setContainer($this->container);

        /* @var RedirectResponse $response */
        $response = $this->cardController->indexAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('http://localhost/account/', $response->getTargetUrl());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param EngineInterface $templatingEngine
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        EngineInterface $templatingEngine
    ) {
        $session = $this->container->get('session');
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $userManager = $this->container->get(UserManager::class);

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
                'router',
                UserService::class,
                TeamService::class,
                UserManager::class,
            ],
            [
                'templating' => $templatingEngine,
            ],
            [
                'stripe_publishable_key',
            ]
        );

        $this->cardController->setContainer($container);

        $response = $this->cardController->indexAction();
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
    {
        return [
            'not in team' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => false,
                            'has_invite' => false,
                        ],
                    ])),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayNotHasKey('team', $parameters);

                            return true;
                        },
                        'return' => new Response(),
                    ],
                ]),
            ],
            'is in team, is leader' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse(array_merge($this->userData, [
                        'team_summary' => [
                            'in' => true,
                            'has_invite' => false,
                        ],
                    ])),
                    HttpResponseFactory::createJsonResponse([
                        'team' => [
                            'leader' => 'user@example.com',
                            'name' => 'Team Name',
                        ],
                        'members' => [],
                    ]),
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertCommonViewData($viewName, $parameters);

                            $this->assertArrayHasKey('team', $parameters);
                            $this->assertInstanceOf(Team::class, $parameters['team']);

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

        $this->assertInternalType('string', $parameters['stripe_publishable_key']);
        $this->assertEquals('Agency', $parameters['plan_presentation_name']);
        $this->assertInstanceOf(UserSummary::class, $parameters['user_summary']);
        $this->assertInternalType('array', $parameters['countries']);

        $this->assertInternalType('int', $parameters['expiry_year_start']);
        $this->assertInternalType('int', $parameters['expiry_year_end']);
        $this->assertEquals(
            CardController::CARD_EXPIRY_DATE_YEAR_RANGE,
            $parameters['expiry_year_end'] - $parameters['expiry_year_start']
        );
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
                'stripe_publishable_key',
                'plan_presentation_name',
                'user_summary',
                'countries',
                'expiry_year_start',
                'expiry_year_end',
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
