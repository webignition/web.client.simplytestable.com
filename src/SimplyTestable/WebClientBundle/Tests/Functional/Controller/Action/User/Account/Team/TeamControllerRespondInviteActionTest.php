<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class TeamControllerRespondInviteActionTest extends AbstractTeamControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_respondinvite';
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

    public function testRespondInviteActionPostRequestPrivateUser()
    {
        $router = $this->container->get('router');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $requestUrl = $router->generate(self::ROUTE_NAME);

        $user = new User('user@example.com');

        $this->client->getCookieJar()->set(
            new Cookie(UserService::USER_COOKIE_KEY, $userSerializerService->serializeToString($user))
        );

        $this->client->request(
            'POST',
            $requestUrl,
            [
                'response' => 'accept',
            ]
        );

        /* @var RedirectResponse $response */
        $response = $this->client->getResponse();

        $this->assertEquals(
            self::EXPECTED_REDIRECT_URL,
            $response->getTargetUrl()
        );
    }

    public function testRespondInviteActionBadRequestResponseValue()
    {
        /* @var RedirectResponse $response */
        $response = $this->teamController->respondInviteAction(new Request());

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @dataProvider respondInviteSuccessDataProvider
     *
     * @param Request $request
     */
    public function testRespondInviteActionSuccess(Request $request)
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->teamController->respondInviteAction($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(self::EXPECTED_REDIRECT_URL, $response->getTargetUrl());
    }

    /**
     * @return array
     */
    public function respondInviteSuccessDataProvider()
    {
        return [
            'accept' => [
                'request' => new Request([], [
                    'response' => 'accept',
                    'team' => 'Foo',
                ]),
            ],
            'decline' => [
                'request' => new Request([], [
                    'response' => 'decline',
                    'team' => 'Foo',
                ]),
            ],
        ];
    }
}
