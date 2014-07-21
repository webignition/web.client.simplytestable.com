<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Team\Team;
use SimplyTestable\WebClientBundle\Exception\Team\Service\Exception as TeamServiceException;

class TeamService extends CoreApplicationService {


    /**
     * @var Team[]
     */
    private $teams = [];

    public function create($name) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('team_create'),
            null,
            array(
                'name' => $name
            ));

        $this->addAuthorisationToRequest($request);
        $response = $request->send();
        return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
    }


    public function getInvite($inviteeEmail) {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('teaminvite_get', [
                'invitee_email' => $inviteeEmail
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            return $request->send()->json();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();

            if ($response->getStatusCode() == 400 && $response->hasHeader('X-TeamInviteGet-Error-Code')) {
                throw new TeamServiceException(
                    (string)$response->getHeader('X-TeamInviteGet-Error-Message'),
                    (int)(string)$response->getHeader('X-TeamInviteGet-Error-Code')
                );
            }

            throw $badResponseException;
        }
    }


    /**
     * @return Team
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\CurlException
     */
    public function getTeam() {
        if (!isset($this->teams[$this->getUser()->getUsername()])) {
            $request = $this->webResourceService->getHttpClientService()->getRequest(
                $this->getUrl('team_get')
            );

            $this->addAuthorisationToRequest($request);

            try {
                $this->teams[$this->getUser()->getUsername()] = new Team($this->webResourceService->get($request)->getContentObject());
            } catch (\Guzzle\Http\Exception\CurlException $curlException) {
                throw $curlException;
            }
        }

        return $this->teams[$this->getUser()->getUsername()];
    }

}