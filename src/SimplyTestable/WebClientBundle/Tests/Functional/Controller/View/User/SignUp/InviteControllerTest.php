<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\SignUp;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\InviteController;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Tests\Factory\ContainerFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InviteControllerTest extends BaseSimplyTestableTestCase
{
    const INVITE_USERNAME = 'user@example.com';
    const TOKEN = 'tokenValue';
    const TEAM_NAME = 'Team Name';

    /**
     * @var InviteController
     */
    private $inviteController;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->inviteController = new InviteController();
    }

    public function testIndexActionGetRequest()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => 'Team Name',
                'user' => self::INVITE_USERNAME,
                'token' => self::TOKEN,
            ]),
        ]);

        $session = $this->container->get('session');
        $flashBag = $session->getFlashBag();

        $flashBag->set(ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY, 'foo');

        $router = $this->container->get('router');
        $requestUrl = $router->generate('view_user_signup_invite_index', [
            'token' => self::TOKEN,
        ]);

        $this->client->request(
            'GET',
            $requestUrl
        );

        /* @var SymfonyResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param array $flashBagValues
     * @param EngineInterface $templatingEngine
     */
    public function testIndexAction(
        array $httpFixtures,
        Request $request,
        array $flashBagValues,
        EngineInterface $templatingEngine
    ) {
        if (!empty($httpFixtures)) {
            $this->setHttpFixtures($httpFixtures);
        }

        $session = $this->container->get('session');

        $flashBag = $session->getFlashBag();

        foreach ($flashBagValues as $key => $value) {
            $flashBag->set($key, $value);
        }

        $containerFactory = new ContainerFactory($this->container);

        $container = $containerFactory->create(
            [
                'simplytestable.services.teaminviteservice',
                'simplytestable.services.cacheableResponseService',
                'simplytestable.services.userservice',
            ],
            [
                'session' => $session,
                'templating' => $templatingEngine,
            ],
            [
                'public_site',
                'external_links',
            ]
        );

        $this->inviteController->setContainer($container);
        $this->inviteController->setRequest($request);

        $this->inviteController->indexAction($request);
    }

    /**
     * @return array
     */
    public function indexActionDataProvider()
    {
        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITE_USERNAME,
            'token' => self::TOKEN,
        ];

        $invite = new Invite($inviteData);

        return [
            'password blank' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($inviteData),
                ],
                'request' => new Request([], [], [
                    'token' => self::TOKEN,
                ]),
                'flashBagValues' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY =>
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) use ($invite) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
                                $viewName
                            );

                            $this->assertEquals(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK,
                                $parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY]
                            );
                            $this->assertNull($parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY]);

                            $this->assertEquals(
                                $invite,
                                $parameters['invite']
                            );
                            $this->assertTrue($parameters['has_invite']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertNull($response);

                            $this->assertTemplateParameters($parameters);

                            return true;
                        },
                        'return' => new SymfonyResponse(),
                    ],
                ]),
            ],
            'get invite failure' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'request' => new Request([], [], [
                    'token' => self::TOKEN,
                ]),
                'flashBagValues' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY =>
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY => 500,
                ],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
                                $viewName
                            );

                            $this->assertEquals(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                                $parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY]
                            );
                            $this->assertEquals(
                                500,
                                $parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY]
                            );

                            $this->assertNull($parameters['invite']);
                            $this->assertFalse($parameters['has_invite']);
                            $this->assertNull($parameters['stay_signed_in']);
                            $this->assertNull($response);

                            $this->assertTemplateParameters($parameters);

                            return true;
                        },
                        'return' => new SymfonyResponse(),
                    ],
                ]),
            ],
            'no errors, stay signed in' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($inviteData),
                ],
                'request' => new Request([
                    'stay-signed-in' => 1,
                ], [], [
                    'token' => self::TOKEN,
                ]),
                'flashBagValues' => [],
                'templatingEngine' => MockFactory::createTemplatingEngine([
                    'renderResponse' => [
                        'withArgs' => function ($viewName, $parameters, $response) use ($invite) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
                                $viewName
                            );

                            $this->assertNull($parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY]);
                            $this->assertNull($parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY]);

                            $this->assertEquals($invite, $parameters['invite']);
                            $this->assertTrue($parameters['has_invite']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);
                            $this->assertNull($response);

                            $this->assertTemplateParameters($parameters);

                            return true;
                        },
                        'return' => new SymfonyResponse(),
                    ],
                ]),
            ],
        ];
    }

    /**
     * @param array $parameters
     */
    private function assertTemplateParameters(array $parameters)
    {
        $publicUser = new User('public', 'public');

        $this->assertInternalType('array', $parameters['public_site']);
        $this->assertInternalType('array', $parameters['external_links']);
        $this->assertEquals(self::TOKEN, $parameters['token']);
        $this->assertEquals($publicUser, $parameters['user']);
        $this->assertFalse($parameters['is_logged_in']);
    }
}
