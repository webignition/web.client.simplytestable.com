<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TeamInvite;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\EntityEnclosingRequest;
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
    const TEAM_NAME = 'Team Name';
    const TOKEN = 'TokenValue';
    const USERNAME = 'user@example.com';

    /**
     * @var TeamInviteService
     */
    private $teamInviteService;

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

        $this->teamInviteService->setUser(new User(self::USERNAME));

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

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);
        $this->teamInviteService->get(self::USERNAME);
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
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                'team' => self::TEAM_NAME,
                'user' => self::USERNAME,
                'token' => self::TOKEN,
            ]),
        ]);

        $invite = $this->teamInviteService->get(self::USERNAME);

        $this->assertInstanceOf(Invite::class, $invite);

        $this->assertEquals(self::TEAM_NAME, $invite->getTeam());
        $this->assertEquals(self::TOKEN, $invite->getToken());
        $this->assertEquals(self::USERNAME, $invite->getUser());

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/invite/user@example.com/', $lastRequest->getUrl());
    }

    public function testGetForUserSuccess()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse([
                [
                    'team' => self::TEAM_NAME,
                    'user' => self::USERNAME,
                    'token' => self::TOKEN,
                ],
            ]),
        ]);

        $invites = $this->teamInviteService->getForUser();

        $this->assertInternalType('array', $invites);
        $invite = $invites[0];

        $this->assertInstanceOf(Invite::class, $invite);

        $this->assertEquals(self::TEAM_NAME, $invite->getTeam());
        $this->assertEquals(self::TOKEN, $invite->getToken());
        $this->assertEquals(self::USERNAME, $invite->getUser());


        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/user/invites/', $lastRequest->getUrl());
    }

    /**
     * @dataProvider booleanResponseDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedReturnValue
     */
    public function testDeclineInvite(array $httpFixtures, $expectedReturnValue)
    {
        $this->setHttpFixtures($httpFixtures);

        $invite = new Invite([
            'team' => self::TEAM_NAME,
        ]);

        $returnValue = $this->teamInviteService->declineInvite($invite);

        $this->assertEquals($expectedReturnValue, $returnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/invite/decline/', $lastRequest->getUrl());
        $this->assertEquals(self::TEAM_NAME, $lastRequest->getPostField('team'));
    }

    /**
     * @dataProvider booleanResponseDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedReturnValue
     */
    public function testAcceptInvite(array $httpFixtures, $expectedReturnValue)
    {
        $this->setHttpFixtures($httpFixtures);

        $invite = new Invite([
            'team' => self::TEAM_NAME,
        ]);

        $returnValue = $this->teamInviteService->acceptInvite($invite);

        $this->assertEquals($expectedReturnValue, $returnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals('http://null/team/invite/accept/', $lastRequest->getUrl());
        $this->assertEquals(self::TEAM_NAME, $lastRequest->getPostField('team'));
    }

    /**
     * @return array
     */
    public function booleanResponseDataProvider()
    {
        return [
            'failure' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 400'),
                ],
                'expectedReturnValue' => false,
            ],
            'success' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'expectedReturnValue' => true,
            ],
        ];
    }
}
