<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TeamControllerLeaveActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_leave';
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

    public function testLeaveActionPostRequestPrivateUser()
    {
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME)
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }
}
