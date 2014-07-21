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

}