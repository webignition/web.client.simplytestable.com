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


    /**
     * @param string $member
     * @return bool
     */
    public function removeFromTeam($member) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('team_remove', [
                'member_email' => $member
            ])
        );

        $this->addAuthorisationToRequest($request);

        return $request->send()->getStatusCode() == 200;
    }

}