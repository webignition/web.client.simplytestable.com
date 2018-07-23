<?php

namespace Tests\AppBundle\Functional\Controller\Action\User\Account\TeamInvite;

use App\Services\UserManager;
use Tests\AppBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use webignition\SimplyTestableUserModel\User;

class TeamInviteControllerRemoveInviteActionTest extends AbstractTeamInviteControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_removeinvite';
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

    public function testRemoveInviteActionPostRequestPrivateUser()
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
                'user' => 'invitee@example.com',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }
}
