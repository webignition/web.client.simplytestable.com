<?php

namespace Tests\WebClientBundle\Functional\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\Action\SignUp\Team\InviteController as ActionInviteController;
use SimplyTestable\WebClientBundle\Controller\View\User\SignUp\InviteController;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Factory\MockFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\WebClientBundle\Functional\Controller\View\AbstractViewControllerTest;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class InviteControllerTest extends AbstractViewControllerTest
{
    const INVITE_USERNAME = 'user@example.com';
    const TOKEN = 'tokenValue';
    const TEAM_NAME = 'Team Name';

    public function testIndexActionGetRequest()
    {
        $this->httpMockHandler->appendFixtures([
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
     * @dataProvider indexActionRenderDataProvider
     *
     * @param array $httpFixtures
     * @param Request $request
     * @param array $flashBagValues
     * @param Twig_Environment $twig
     *
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testIndexActionRender(
        array $httpFixtures,
        Request $request,
        array $flashBagValues,
        Twig_Environment $twig
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $session = $this->container->get('session');

        $flashBag = $session->getFlashBag();

        foreach ($flashBagValues as $key => $value) {
            $flashBag->set($key, $value);
        }

        /* @var InviteController $inviteController */
        $inviteController = $this->container->get(InviteController::class);
        $this->setTwigOnController($twig, $inviteController);

        $inviteController->indexAction($request, self::TOKEN);
    }

    /**
     * @return array
     */
    public function indexActionRenderDataProvider()
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
                'flashBagValues' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY =>
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_PASSWORD_BLANK
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) use ($invite) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
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
                'flashBagValues' => [
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_KEY =>
                        ActionInviteController::FLASH_BAG_INVITE_ACCEPT_ERROR_MESSAGE_FAILURE,
                    ActionInviteController::FLASH_BAG_INVITE_ACCEPT_FAILURE_KEY => 500,
                ],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:invalid.html.twig',
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
                'flashBagValues' => [],
                'twig' => MockFactory::createTwig([
                    'render' => [
                        'withArgs' => function ($viewName, $parameters) use ($invite) {
                            $this->assertEquals(
                                'SimplyTestableWebClientBundle:bs3/User/SignUp/Invite:index.html.twig',
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

        $this->container->get('request_stack')->push($request);

        /* @var InviteController $inviteController */
        $inviteController = $this->container->get(InviteController::class);

        $response = $inviteController->indexAction($request, self::TOKEN);
        $this->assertInstanceOf(SymfonyResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseLastModified = new \DateTime($response->headers->get('last-modified'));
        $responseLastModified->modify('+1 hour');

        $newRequest = $request->duplicate();

        $newRequest->headers->set('if-modified-since', $responseLastModified->format('c'));
        $newResponse = $inviteController->indexAction($newRequest, self::TOKEN);

        $this->assertInstanceOf(SymfonyResponse::class, $newResponse);
        $this->assertEquals(304, $newResponse->getStatusCode());
    }

    /**
     * @param array $parameters
     */
    private function assertTemplateParameters(array $parameters)
    {
        $publicUser = new User('public', 'public');

        $this->assertEquals(self::TOKEN, $parameters['token']);
        $this->assertEquals($publicUser, $parameters['user']);
        $this->assertFalse($parameters['is_logged_in']);
    }
}
