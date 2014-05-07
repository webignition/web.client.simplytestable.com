<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Controller\View\CacheableViewController;

use Symfony\Component\HttpFoundation\Cookie;

class IndexController extends CacheableViewController implements IEFiltered {
    
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
            'email' => trim($this->getRequest()->query->get('email')),
            'plan' => trim($this->getRequest()->query->get('plan')),
            'redirect' => trim($this->getRequest()->query->get('redirect'))
        );
    }
    
    


    
    public function getCacheValidatorParameters() {        
        return $this->getTransientViewData(false);
    }

}