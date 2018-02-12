<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Message\EntityEnclosingRequest;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TeamService;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

class TeamServiceTest extends AbstractCoreApplicationServiceTest
{
    const TEAM_NAME = 'Team Name';

    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamService = $this->container->get(
            'simplytestable.services.teamservice'
        );

        $this->user = new User('user@example.com');
        $this->teamService->setUser($this->user);
        $this->coreApplicationHttpClient->setUser($this->user);
    }

    public function testCreate()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->create(self::TEAM_NAME);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/create/', $lastRequest->getUrl());
        $this->assertEquals(self::TEAM_NAME, $lastRequest->getPostField('name'));
    }

    public function testGetTeam()
    {
        $teamData = (object)[
            'team' => (object)[
                'name' => self::TEAM_NAME,
                'leader' => 'leader@example.com',
            ],
            'members' => [],
        ];

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createJsonResponse($teamData),
        ]);

        $team = $this->teamService->getTeam();

        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(new Team($teamData), $team);

        $lastRequest = $this->getLastRequest();

        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/', $lastRequest->getUrl());
    }

    public function testRemoveFromTeam()
    {
        $memberEmail = 'member@example.com';

        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->removeFromTeam($memberEmail);

        $lastRequest = $this->getLastRequest();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/remove/' . $memberEmail . '/', $lastRequest->getUrl());
    }

    public function testLeave()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->leave();

        $lastRequest = $this->getLastRequest();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/leave/', $lastRequest->getUrl());
    }
}
