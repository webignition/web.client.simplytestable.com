<?php

namespace Tests\WebClientBundle\Functional\Controller\Action\User\Account\Team;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
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
        $userManager = $this->container->get(UserManager::class);

        $userManager->setUser(new User('user@example.com'));

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->client->request(
            'POST',
            $this->createRequestUrl(self::ROUTE_NAME),
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
     *
     * @throws CoreApplicationReadOnlyException
     * @throws InvalidCredentialsException
     */
    public function testRespondInviteActionSuccess(Request $request)
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
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
