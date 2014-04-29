<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;
use Symfony\Component\HttpFoundation\Cookie;

class SignUpConfirmController extends BaseViewController implements IEFiltered, Cacheable {
    
    const ONE_YEAR_IN_SECONDS = 31536000;       
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction() {
        $notificationKeys = array(
            'token_resend_confirmation',
            'user_create_confirmation',
            'user_token_error',
            'token_resend_error',
            'user_error'            
        );
        
        $email = trim($this->get('request')->get('email'));
        $userError = ($this->getUserService()->exists($email)) ? '' : 'invalid-user';
        
        $viewData = $this->getTransientViewData();
        $viewData['user_error'] = $userError;
        
        if ($viewData['token_resend_error'] == 'invalid-user') {
            $viewData['token_resend_error'] = '';
            $viewData['user_error'] = 'invalid-user';
        }
        
        $viewData['has_notification'] = $this->hasNotification($notificationKeys, $viewData);      
        
        return $this->renderCacheableResponse($viewData);

    }
    
    
    private function getTransientViewData($flush = true) {        
        return array(
            'token_resend_confirmation' => $this->getFlash('token_resend_confirmation', $flush),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation', $flush),
            'user_token_error' => $this->getFlash('user_token_error', $flush),
            'token_resend_error' => $this->getFlash('token_resend_error', $flush),            
            'token' => $this->get('request')->query->get('token'), 
        );
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }
    
    

    private function hasNotification($notificationKeys, $viewData) {
        foreach ($notificationKeys as $notificationKey) {
            if (array_key_exists($notificationKey, $viewData) && !empty($viewData[$notificationKey])) {
                return true;
            }
        }
        
        return false;
    }    

}