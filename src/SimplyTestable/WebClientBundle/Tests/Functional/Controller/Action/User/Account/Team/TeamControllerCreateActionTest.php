<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamControllerCreateActionTest extends AbstractTeamControllerTest
{
    const TEAM_NAME = 'Team Name';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/team/';

    public function testCreateActionPostRequestPublicUser()
    {
        $router = $this->container->get('router');
        $requestUrl = $router->generate('action_user_account_team_create');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            'http://localhost/signin/?redirect=eyJyb3V0ZSI6InZpZXdfdXNlcl9hY2NvdW50X2luZGV4X2luZGV4In0%3D',
            $response->getTargetUrl()
        );
    }

    public function testCreateActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $requestUrl = $router->generate('action_user_account_team_create');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $user = new User('user@example.com', 'password');

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'name' => self::TEAM_NAME,
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    public function testCreateActionEmptyName()
    {
        $session = $this->container->get('session');

        $this->container->set('request', new Request());

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(
            [
                TeamController::FLASH_BAG_CREATE_ERROR_KEY => [
                    TeamController::FLASH_BAG_CREATE_ERROR_MESSAGE_NAME_BLANK,
                ],
            ],
            $session->getFlashBag()->peekAll()
        );
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    public function testCreateActionSuccess()
    {
        $session = $this->container->get('session');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->container->set('request', new Request([], [
            'name' => self::TEAM_NAME,
        ]));

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals([], $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }
}
