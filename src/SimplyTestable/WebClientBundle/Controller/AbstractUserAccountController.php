<?php

namespace SimplyTestable\WebClientBundle\Controller;

abstract class AbstractUserAccountController extends BaseViewController
{  
    protected function getNotLoggedInResponse() {
        if ($this->isLoggedIn()) {
            return null;
        }
        
        $redirectParameters = json_encode(array(
            'route' => 'user_account_index'
        ));

        $this->get('session')->setFlash('user_signin_error', 'account-not-logged-in');

        return $this->redirect($this->generateUrl('sign_in', array(
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
}