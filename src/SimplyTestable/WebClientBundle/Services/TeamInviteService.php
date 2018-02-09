<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;

class TeamInviteService
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
     * @param $inviteeEmail
     * @return Invite
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws TeamServiceException
     */
    public function get($inviteeEmail)
    {
        try {
            $response = $this->coreApplicationHttpClient->get('teaminvite_get', [
                'invitee_email' => $inviteeEmail,
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if (400 === $coreApplicationRequestException->getCode()) {
                $response = $coreApplicationRequestException->getResponse();

                if ($response->getStatusCode() == 400 && $response->hasHeader('X-TeamInviteGet-Error-Code')) {
                    throw new TeamServiceException(
                        (string)$response->getHeader('X-TeamInviteGet-Error-Message'),
                        (int)(string)$response->getHeader('X-TeamInviteGet-Error-Code')
                    );
                }
            }

            throw $coreApplicationRequestException;
        }

        $responseData = $this->jsonResponseHandler->handle($response);

        return new Invite($responseData);
    }

    /**
     * @return Invite[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getForUser()
    {
        $response = $this->coreApplicationHttpClient->get('teaminvite_userlist');
        $inviteData = $this->jsonResponseHandler->handle($response);

        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     *
     * @throws CoreApplicationReadOnlyException
     * @throws InvalidCredentialsException
     */
    public function declineInvite(Invite $invite)
    {
        try {
            $this->coreApplicationHttpClient->post('teaminvite_decline', [], [
                'team' => $invite->getTeam()
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return false;
        }

        return true;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     *
     * @throws InvalidCredentialsException
     * @throws CoreApplicationReadOnlyException
     */
    public function acceptInvite(Invite $invite)
    {
        try {
            $this->coreApplicationHttpClient->post('teaminvite_accept', [], [
                'team' => $invite->getTeam()
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return false;
        }

        return true;
    }

    /**
     * @return Invite[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getForTeam()
    {
        $response = $this->coreApplicationHttpClient->get('team_invites');
        $inviteData = $this->jsonResponseHandler->handle($response);

        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }

    /**
     * @param Invite $invite
     *
     * @return bool
     *
     * @throws CoreApplicationReadOnlyException
     * @throws InvalidCredentialsException
     */
    public function removeForUser(Invite $invite)
    {
        try {
            $this->coreApplicationHttpClient->post('teaminvite_remove', [
                'invitee_email' => $invite->getUser(),
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return false;
        }

        return true;
    }

    /**
     * @param $token
     * @return Invite|null
     *
     * @throws InvalidContentTypeException
     * @throws InvalidAdminCredentialsException
     */
    public function getForToken($token)
    {
        try {
            $response = $this->coreApplicationHttpClient->getAsAdmin('teaminvite_getbytoken', [
                'token' => $token,
            ]);

            $responseData = $this->jsonResponseHandler->handle($response);

            return new Invite($responseData);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
        }

        return null;
    }
}
