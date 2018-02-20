<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Symfony\Component\BrowserKit\Cookie;
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
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get(UserSerializerService::class);

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $user = new User('user@example.com');

        $this->client->getCookieJar()->set(
            new Cookie(UserManager::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }
}
