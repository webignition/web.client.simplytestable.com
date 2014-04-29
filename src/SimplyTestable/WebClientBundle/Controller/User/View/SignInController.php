<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View;

class SignInController extends ViewController {
    
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
            'email' => $this->getRequest()->query->get('email'),
            'user_signin_error' => $this->getFlash('user_signin_error', $flush),
            'user_signin_confirmation' => $this->getFlash('user_signin_confirmation', $flush),
            'redirect' => $this->getRequest()->query->get('redirect'),
            'stay_signed_in' => $this->getRequest()->query->get('stay-signed-in')
        );
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}