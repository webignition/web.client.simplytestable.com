<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;

class UserEmailChangeRequestService extends UserService {    
    
    /**
     *
     * @var array
     */
    private $emailChangeRequestCache = array();   

    
    /**
     * 
     * @param string $email
     * @return boolean
     */
    public function hasEmailChangeRequest($email) {
        return !is_null($this->getEmailChangeRequest($email));
    }
    
    
    /**
     * 
     * @param string $email
     * @return stdClass
     */
    public function getEmailChangeRequest($email) {         
        if (!isset($this->emailChangeRequestCache[$email])) {
            $response = json_decode($this->getAdminResponse($this->webResourceService->getHttpClientService()->getRequest($this->getUrl('user_email_change_request_get', array(
                'email' => $email
            ))))->getBody());
            
            if (isset($response->error)) {
                return null;
            }
            
            $this->emailChangeRequestCache[$email] = (array)$response;
        }
        
        return $this->emailChangeRequestCache[$email];        
    }
    
    
    public function cancelEmailChangeRequest() {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_email_change_request_cancel', array(
                'email' => $this->getUser()->getUsername()
            ))
        );
        
        $this->addAuthorisationToRequest($request);        
        
        try {
            $response = $request->send();             
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {            
            return $badResponseException->getResponse()->getStatusCode();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }         
    }
    
    
    public function createEmailChangeRequest($newEmail) {        
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_email_change_request_create', array(
                'email' => $this->getUser()->getUsername(),
                'new_email' => $newEmail
            ))
        );
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $response = $request->send();
            
            return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
        } catch (\Guzzle\Http\Exception\BadResponseException $badResponseException) {
            return $badResponseException->getResponse()->getStatusCode();
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return $curlException->getErrorNo();
        }        
    }
    
}