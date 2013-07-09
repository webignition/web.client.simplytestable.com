<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
//use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
//use SimplyTestable\WebClientBundle\Exception\UserServiceException;


class UserPlanSubscriptionService extends UserService {
    
    public function subscribe(User $user, $plan) {        
        $request = $this->webResourceService->getHttpClientService()->postRequest(
                $this->getUrl('user_plan_subscribe', array(
                    'email' => $user->getUsername(),
                    'plan' => $plan
                ))
        );
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {            
            $response = $badResponseException->getResponse();            
            if ($this->httpResponseHasStripeError($response)) {
                throw $this->getUserAccountCardExceptionFromHttpResponse($response);
            }
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }        
    }

    
}