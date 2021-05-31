<?php
namespace App\Services;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Model\Team\Team;

class TeamService
{
    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     * @param JsonResponseHandler $jsonResponseHandler
     */
    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
    }

    /**
     * @param string $name
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function create($name)
    {
        $this->coreApplicationHttpClient->post('team_create', [], [
            'name' => $name,
        ]);
    }

    /**
     * @return Team
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function getTeam()
    {
        $response = $this->coreApplicationHttpClient->get('team_get');

        $responseData = $this->jsonResponseHandler->handle($response);

        return new Team($responseData);
    }

    /**
     * @param string $member
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function removeFromTeam($member)
    {
        $this->coreApplicationHttpClient->post('team_remove', [
            'member_email' => $member,
        ]);
    }

    /**
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function leave()
    {
        $this->coreApplicationHttpClient->post('team_leave');
    }
}
