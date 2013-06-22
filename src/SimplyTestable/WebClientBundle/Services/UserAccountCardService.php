<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;

class UserAccountCardService extends UserService {
    
    public function associate(User $user, $stripe_card_token) {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
                $this->getUrl('user_card_associate', array(
                    'email' => $user->getUsername(),
                    'stripe_card_token' => $stripe_card_token
                ))
        );
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();            
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
//            var_dump($badResponseException->getResponse());
//            exit();
            
            return $badResponseException->getResponse()->getStatusCode();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
//            var_dump($curlException);
//            exit();
            return $curlException->getErrorNo();
        } 
    }
    
}