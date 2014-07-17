<?php
namespace SimplyTestable\WebClientBundle\Services;

class TeamService extends CoreApplicationService {

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
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }
    }

}