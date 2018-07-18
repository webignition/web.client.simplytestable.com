<?php

namespace Tests\WebClientBundle\Functional\Services;

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

        $this->teamService = self::$container->get(TeamService::class);
    }

    public function testCreate()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->create(self::TEAM_NAME);

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/create/', $lastRequest->getUri());
        $this->assertEquals(self::TEAM_NAME, $postedData['name']);
    }

    public function testGetTeam()
    {
        $teamData = [
            'team' => [
                'name' => self::TEAM_NAME,
                'leader' => 'leader@example.com',
            ],
            'members' => [],
        ];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($teamData),
        ]);

        $team = $this->teamService->getTeam();

        $this->assertInstanceOf(Team::class, $team);
        $this->assertEquals(new Team($teamData), $team);

        $lastRequest = $this->httpHistory->getLastRequest();

        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/', $lastRequest->getUri());
    }

    public function testRemoveFromTeam()
    {
        $memberEmail = 'member@example.com';

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->removeFromTeam($memberEmail);

        $lastRequest = $this->httpHistory->getLastRequest();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/remove/' . $memberEmail . '/', $lastRequest->getUri());
    }

    public function testLeave()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->teamService->leave();

        $lastRequest = $this->httpHistory->getLastRequest();

        $this->assertEquals('POST', $lastRequest->getMethod());
        $this->assertEquals('http://null/team/leave/', $lastRequest->getUri());
    }
}
