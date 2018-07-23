<?php

namespace App\Tests\Functional\Controller\Action\User\Account\Team;

use App\Controller\Action\User\Account\TeamController;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use webignition\SimplyTestableUserModel\User;

class TeamControllerCreateActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_create';
    const TEAM_NAME = 'Team Name';
    const EXPECTED_REDIRECT_URL = '/account/team/';

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
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->router->generate(self::ROUTE_NAME),
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
        $session = self::$container->get('session');

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
        $session = self::$container->get('session');

        $this->httpMockHandler->appendFixtures([
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
