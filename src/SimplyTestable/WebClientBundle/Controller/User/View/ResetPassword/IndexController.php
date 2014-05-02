<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View\ResetPassword;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\User\View\CacheableViewController;

class IndexController extends CacheableViewController implements IEFiltered {
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction() {             
        return $this->renderCacheableResponse($this->getTransientViewData());        
    }
    
    
    private function getTransientViewData($flush = true) {        
        return array(
            'email' => trim($this->getRequest()->query->get('email')),
            'user_reset_password_error' => $this->getFlash('user_reset_password_error', $flush),
            'user_reset_password_confirmation' => $this->getFlash('user_reset_password_confirmation', $flush)
        );
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}