<?php

namespace SimplyTestable\WebClientBundle\Controller;

abstract class AbstractUserAccountController extends BaseViewController
{  
    protected function getNotLoggedInResponse() {
        if ($this->isLoggedIn()) {
            return null;
        }
        
        $redirectParameters = json_encode(array(
            'route' => 'user_view_account_index_index'
        ));

        $this->get('session')->setFlash('user_signin_error', 'account-not-logged-in');

        return $this->redirect($this->generateUrl('view_user_signin_index', array(
            'redirect' => base64_encode($redirectParameters)
        ), true));           
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService
     */
    protected function getUserEmailChangeRequestService() {
        return $this->get('simplytestable.services.useremailchangerequestservice');
    }  
    
    /**
     * 
     * @param array $keys
     * @return array
     */
    protected function getViewFlashValues($keys) {
        $flashValues = array();
        
        foreach ($keys as $key) {
            $flashValues[$key] = $this->getFlash($key);
        }
        
        return $flashValues;
    }    
}