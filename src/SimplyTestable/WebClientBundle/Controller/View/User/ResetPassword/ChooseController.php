<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;

class ChooseController extends CacheableViewController implements IEFiltered {
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction($email, $token) {
        return $this->renderCacheableResponse($this->getTransientViewData());
    }
    
    
    private function getTransientViewData($flush = true) {        
        $email = trim($this->getRequest()->attributes->get('email'));
        $token = trim($this->getRequest()->attributes->get('token'));
        
        $actualToken = $this->getUserService()->getConfirmationToken($email);
        
        $userResetPasswordError = $this->getFlash('user_reset_password_error', $flush);                
        if ($userResetPasswordError == '' && $token != $actualToken) {
            $userResetPasswordError = 'invalid-token';
        }        
        
        $viewData = array(
            'email' => $email,
            'token' => $token,
            'stay_signed_in' => $this->getRequest()->query->get('stay-signed-in'),
            'user_reset_password_error' => $userResetPasswordError
        );        
     
        return $viewData;
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}