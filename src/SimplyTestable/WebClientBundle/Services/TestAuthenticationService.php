<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\User;

class TestAuthenticationService extends CoreApplicationService {
    
    public function authenticate(Test $test, User $user) {
        if ($test->getUser() == $user->getUsername()) {
            return true;
        }
        
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_is_public', array(
            'canonical-url' => urlencode($test->getWebsite()),
            'test_id' => $test->getTestId()
        )));        
        
        $this->addAuthorisationToRequest($request);
        
        try {
            $this->webResourceService->get($request);
            return true;
        } catch (WebResourceException $webResourceException) {
            if ($webResourceException->getCode() == 404) {
                return false;
            }
            
            throw $webResourceException;
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            throw $curlException;
        }     
    }  
    
}