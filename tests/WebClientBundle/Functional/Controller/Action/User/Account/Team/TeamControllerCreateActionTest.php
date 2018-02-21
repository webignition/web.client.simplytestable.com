<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Controller\Action\User\Account\TeamController;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamControllerCreateActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_create';
    const TEAM_NAME = 'Team Name';
    const EXPECTED_REDIRECT_URL = 'http://localhost/account/team/';

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

    public function testCreateActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

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

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction(new Request());

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

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->teamController->createAction(new Request([], [
            'name' => self::TEAM_NAME,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals([], $session->getFlashBag()->peekAll());
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }
}
