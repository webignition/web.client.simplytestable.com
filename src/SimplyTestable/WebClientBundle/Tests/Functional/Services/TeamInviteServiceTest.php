<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TeamInvite;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TeamInviteService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;

class TeamInviteServiceTest extends BaseSimplyTestableTestCase
{
    /**
     * @var TeamInviteService
     */
    private $teamInviteService;

    /**
     * @var User
     */
    private $user;

    /**
     * @var HistoryPlugin
     */
    private $httpHistoryPlugin;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamInviteService = $this->container->get(
            'simplytestable.services.teaminviteservice'
        );

        $this->user = new User('user@example.com');

        $this->httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->getHttpClientService();
        $httpClientService->get()->addSubscriber($this->httpHistoryPlugin);
    }

    /**
     * @dataProvider getRemoteFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param string $expectedExceptionCode
     *
     * @throws TeamServiceException
     * @throws WebResourceException
     */
    public function testGetRemoteFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setHttpFixtures($httpFixtures);
        $this->teamInviteService->setUser($this->user);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);
        $this->teamInviteService->get($this->user->getUsername());
    }

    /**
     * @return array
     */
    public function getRemoteFailureDataProvider()
    {
        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedException' => WebResourceException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedException' => WebResourceException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedException' => CurlException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'Application-level error' => [
                'httpFixtures' => [
                    Response::fromMessage(
                        "HTTP/1.1 400\nX-TeamInviteGet-Error-Code:1\nX-TeamInviteGet-Error-Message:foo"
                    ),
                ],
                'expectedException' => TeamServiceException::class,
                'expectedExceptionMessage' => 'foo',
                'expectedExceptionCode' => 1,
            ],
        ];
    }

    public function testGetSuccess()
    {
        $teamName = 'Team Name';
        $token = 'TokenValueHere';

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => $teamName,
                'user' => $this->user->getUsername(),
                'token' => $token,
            ]),
        ]);
        $this->teamInviteService->setUser($this->user);

        $invite = $this->teamInviteService->get($this->user->getUsername());

        $this->assertInstanceOf(Invite::class, $invite);

        $this->assertEquals($teamName, $invite->getTeam());
        $this->assertEquals($token, $invite->getToken());
        $this->assertEquals($this->user->getUsername(), $invite->getUser());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/invite/user@example.com/', $lastRequest->getUrl());
    }

    public function testGetForUserSuccess()
    {
        $teamName = 'Team Name';
        $token = 'TokenValueHere';

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                [
                    'team' => $teamName,
                    'user' => $this->user->getUsername(),
                    'token' => $token,
                ],
            ]),
        ]);
        $this->teamInviteService->setUser($this->user);

        $invites = $this->teamInviteService->getForUser();

        $this->assertInternalType('array', $invites);
        $invite = $invites[0];

        $this->assertInstanceOf(Invite::class, $invite);

        $this->assertEquals($teamName, $invite->getTeam());
        $this->assertEquals($token, $invite->getToken());
        $this->assertEquals($this->user->getUsername(), $invite->getUser());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/user/invites/', $lastRequest->getUrl());
    }
}
