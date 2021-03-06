<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Controller\View\User\SignUp;

use App\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
use App\Controller\View\User\SignUp\InviteController;
use App\Model\Team\Invite;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\MockFactory;
use App\Tests\Services\SymfonyRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use App\Tests\Functional\Controller\View\AbstractViewControllerTest;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class InviteControllerTest extends AbstractViewControllerTest
{
    const ROUTE_NAME = 'view_user_sign_up_invite';
    const INVITE_USERNAME = 'user@example.com';
    const TOKEN = 'tokenValue';
    const TEAM_NAME = 'Team Name';

    /**
     * @var array
     */
    private $routeParameters = [
        'token' => self::TOKEN,
    ];

    public function testIsIEFiltered()
    {
        $this->issueIERequest(self::ROUTE_NAME, $this->routeParameters);
        $this->assertIEFilteredRedirectResponse();
    }

    public function testIndexActionGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => 'Team Name',
                'user' => self::INVITE_USERNAME,
                'token' => self::TOKEN,
            ]),
        ]);

        $flashBag = self::$container->get(FlashBagInterface::class);
        $flashBag->set(ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY, 'foo');

        $this->client->request(
            'GET',
            $this->router->generate(self::ROUTE_NAME, $this->routeParameters)
        );

        /* @var SymfonyResponse $response */
        $response = $this->client->getResponse();

        $this->assertTrue($response->isSuccessful());
    }

    /**
     * @dataProvider indexActionRenderDataProvider
     */
    public function testIndexActionRender(
        array $httpFixtures,
        Request $request,
        array $flashBagMessages,
        Twig_Environment $twig
    ) {
        $session = self::$container->get(SessionInterface::class);
        $flashBag = self::$container->get(FlashBagInterface::class);

        $session->start();

        $this->httpMockHandler->appendFixtures($httpFixtures);
        $flashBag->setAll($flashBagMessages);

        /* @var InviteController $inviteController */
        $inviteController = self::$container->get(InviteController::class);
        $this->setTwigOnController($twig, $inviteController);

        $inviteController->indexAction($request, self::TOKEN);
    }

    public function indexActionRenderDataProvider(): array
    {
        $inviteData = [
            'team' => self::TEAM_NAME,
            'user' => self::INVITE_USERNAME,
            'token' => self::TOKEN,
        ];

        $invite = new Invite($inviteData);

        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();

        return [
            'password blank' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse($inviteData),
                ],
                'request' => new Request(),
                'flashBagMessages' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY => [
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
                    ],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) use ($invite) {
                            $this->assertEquals(
                                'user-sign-up-invite.html.twig',
                                $viewName
                            );

                            $this->assertEquals(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK,
                                $parameters[ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY]
                            );
                            $this->assertArrayNotHasKey(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
                                $parameters
                            );

                            $this->assertEquals($invite, $parameters['invite']);
                            $this->assertNull($parameters['stay_signed_in']);

                            $this->assertTemplateParameters($parameters);

                            return true;
                        },
                        'return' => new SymfonyResponse(),
                    ],
                ]),
            ],
            'get invite failure' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'request' => new Request(),
                'flashBagMessages' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY => [
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                    ],
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY => [500],
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(
                                'user-sign-up-invite-invalid-token.html.twig',
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
                            $this->assertNull($parameters['stay_signed_in']);

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
                ]),
                'flashBagMessages' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) use ($invite) {
                            $this->assertEquals(
                                'user-sign-up-invite.html.twig',
                                $viewName
                            );

                            $this->assertArrayNotHasKey(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY,
                                $parameters
                            );
                            $this->assertArrayNotHasKey(
                                ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY,
                                $parameters
                            );

                            $this->assertEquals($invite, $parameters['invite']);
                            $this->assertEquals(1, $parameters['stay_signed_in']);

                            $this->assertTemplateParameters($parameters);

                            return true;
                        },
                        'return' => new SymfonyResponse(),
                    ],
                ]),
            ],
        ];
    }

    public function testIndexActionCachedResponse()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => self::TEAM_NAME,
                'user' => self::INVITE_USERNAME,
                'token' => self::TOKEN,
            ]),
        ]);

        $request = new Request();

        self::$container->get('request_stack')->push($request);

        /* @var InviteController $inviteController */
        $inviteController = self::$container->get(InviteController::class);

        $response = $inviteController->indexAction($request, self::TOKEN);
        $this->assertInstanceOf(SymfonyResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $requestFactory = self::$container->get(SymfonyRequestFactory::class);
        $newRequest = $requestFactory->createFollowUpRequest($request, $response);

        $newResponse = $inviteController->indexAction($newRequest, self::TOKEN);

        $this->assertInstanceOf(SymfonyResponse::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    private function assertTemplateParameters(array $parameters)
    {
        $publicUser = new User('public', 'public');

        $this->assertEquals(self::TOKEN, $parameters['token']);
        $this->assertEquals($publicUser, $parameters['user']);
        $this->assertFalse($parameters['is_logged_in']);
    }
}
