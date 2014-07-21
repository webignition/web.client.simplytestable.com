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


//    /**
//     * @return Team
//     * @throws \Exception
//     * @throws \Guzzle\Http\Exception\CurlException
//     */
//    public function getTeam() {
//        if (!isset($this->teams[$this->getUser()->getUsername()])) {
//            $request = $this->webResourceService->getHttpClientService()->getRequest(
//                $this->getUrl('team_get')
//            );
//
//            $this->addAuthorisationToRequest($request);
//
//            try {
//                $this->teams[$this->getUser()->getUsername()] = new Team($this->webResourceService->get($request)->getContentObject());
//            } catch (\Guzzle\Http\Exception\CurlException $curlException) {
//                throw $curlException;
//            }
//        }
//
//        return $this->teams[$this->getUser()->getUsername()];
//    }

}