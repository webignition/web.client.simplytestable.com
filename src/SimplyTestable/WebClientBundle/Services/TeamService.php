<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Model\Team\Team;

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

        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            if ($badResponseException->getResponse()->getStatusCode() == 401) {
                throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
            }

            return $badResponseException->getResponse()->getStatusCode();
        }
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
            if ($badResponseException->getResponse()->getStatusCode() == 401) {
                throw new CoreApplicationAdminRequestException('Invalid admin user credentials', 401);
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