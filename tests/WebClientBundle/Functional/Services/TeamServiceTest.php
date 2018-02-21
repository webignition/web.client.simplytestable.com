<?php

namespace Tests\WebClientBundle\Functional\Services;

use GuzzleHttp\Post\PostBody;
use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Services\TeamService;
use Tests\WebClientBundle\Factory\HttpResponseFactory;

class TeamServiceTest extends AbstractCoreApplicationServiceTest
{
    const TEAM_NAME = 'Team Name';

    /**
     * @var TeamService
     */
    private $teamService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->teamService = $this->container->get(TeamService::class);
    }

    public function testCreate()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->create(self::TEAM_NAME);

        $lastRequest = $this->getLastRequest();

        /* @var PostBody $requestBody */
        $requestBody = $lastRequest->getBody();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/create/', $lastRequest->getUrl());
        $this->assertEquals(self::TEAM_NAME, $requestBody->getField('name'));
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
