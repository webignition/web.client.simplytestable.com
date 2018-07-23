<?php

namespace App\Tests\Functional\Controller\Action\User\Account\TeamInvite;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\InvalidCredentialsException;
use App\Services\UserManager;
use App\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use webignition\SimplyTestableUserModel\User;

class TeamInviteControllerRespondInviteActionTest extends AbstractTeamInviteControllerTest
{
    const ROUTE_NAME = 'action_user_account_team_respondinvite';
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

    public function testRespondInviteActionPostRequestPrivateUser()
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
        $response = $this->teamInviteController->respondInviteAction(new Request());

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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        /* @var RedirectResponse $response */
        $response = $this->teamInviteController->respondInviteAction($request);

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
