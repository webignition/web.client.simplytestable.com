<?php

namespace SimplyTestable\WebClientBundle\Controller\User\View;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Cacheable;
use Symfony\Component\HttpFoundation\Cookie;

class SignUpController extends BaseViewController implements IEFiltered, Cacheable {
    
    const ONE_YEAR_IN_SECONDS = 31536000;       
    
    protected function modifyViewName($viewName) {
        return str_replace(':User', ':bs3/User', $viewName);
    }
    
    public function indexAction() {
        $response = $this->renderCacheableResponse($this->getTransientViewData());
        
        $redirect = trim($this->get('request')->query->get('redirect'));        
        if ($redirect !== '') {
            $cookie = new Cookie(
                'simplytestable-redirect',
                $redirect,
                time() + self::ONE_YEAR_IN_SECONDS,
                '/',
                '.simplytestable.com',
                false,
                true
            );
            
            $response->headers->setCookie($cookie);            
        }
        
        return $response;
    }
    
    
    private function getTransientViewData($flush = true) {        
        return array(
            'user_create_error' => $this->getFlash('user_create_error', $flush),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation', $flush),
            'email' => $this->getPersistentValue('email'),
            'plan' => $this->getPersistentValue('plan'),
            'redirect' => trim($this->get('request')->query->get('redirect'))
        );
    }

    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}