<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;

class TeamInviteService extends CoreApplicationService {


    /**
     * @param $inviteeEmail
     * @return Invite
     * @throws \SimplyTestable\WebClientBundle\Exception\Team\Service\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function get($inviteeEmail) {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('teaminvite_get', [
                'invitee_email' => $inviteeEmail
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            return new Invite($this->webResourceService->get($request)->getContentObject());
        } catch (WebResourceException $webResourceException) {
            $response = $webResourceException->getResponse();

            if ($response->getStatusCode() == 400 && $response->hasHeader('X-TeamInviteGet-Error-Code')) {
                throw new TeamServiceException(
                    (string)$response->getHeader('X-TeamInviteGet-Error-Message'),
                    (int)(string)$response->getHeader('X-TeamInviteGet-Error-Code')
                );
            }

            throw $webResourceException;
        }
    }


    /**
     * @return Invite[]
     */
    public function getForUser() {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('teaminvite_userlist')
        );

        $this->addAuthorisationToRequest($request);

        $inviteData = $this->webResourceService->get($request)->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }


    public function declineInvite(Invite $invite) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('teaminvite_decline'),
            null,
            [
                'team' => $invite->getTeam()
            ]
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            return false;
        }
    }


    /**
     * @return Invite[]
     */
    public function getForTeam() {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('team_invites')
        );

        $this->addAuthorisationToRequest($request);

        $inviteData = $this->webResourceService->get($request)->getContentObject();
        $invites = [];

        foreach ($inviteData as $rawInvite) {
            $invites[] = new Invite($rawInvite);
        }

        return $invites;
    }


    public function removeForUser(Invite $invite) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('teaminvite_remove', [
                'invitee_email' => $invite->getUser()
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            return false;
        }
    }

}