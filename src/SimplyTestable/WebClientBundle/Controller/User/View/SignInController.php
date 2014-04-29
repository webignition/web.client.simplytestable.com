<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;

class SignInController extends BaseViewController implements IEFiltered, Cacheable {
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction() {       
        if ($this->isLoggedIn()) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        return $this->renderCacheableResponse($this->getTransientViewData());   
    }
    
    
    private function getTransientViewData($flush = true) {        
        return array(
            'email' => $this->getPersistentValue('email'),
            'user_signin_error' => $this->getFlash('user_signin_error', $flush),
            'user_signin_confirmation' => $this->getFlash('user_signin_confirmation', $flush),
            'redirect' => $this->getPersistentValue('redirect'),
            'stay_signed_in' => $this->getPersistentValue('stay-signed-in')
        );
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}