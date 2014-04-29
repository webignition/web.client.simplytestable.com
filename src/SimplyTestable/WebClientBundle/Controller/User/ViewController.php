<?php

namespace SimplyTestable\WebClientBundle\Controller\User;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Cookie;

class ViewController extends BaseViewController implements IEFiltered {     
    const ONE_YEAR_IN_SECONDS = 31536000; 
    
    
    public function resetPasswordChooseAction($email, $token) {   
        
        $actualToken = $this->getUserService()->getConfirmationToken($email);
        
        $templateName = 'SimplyTestableWebClientBundle:bs3/User:reset-password-choose.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $userResetPasswordError = $this->getFlash('user_reset_password_error');
        if ($userResetPasswordError == '' && $token != $actualToken) {
            $userResetPasswordError = 'invalid-token';
        }
       
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'email' => $email,
            'user_password_reset_error' => $userResetPasswordError
        ));      
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);

        return $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'email' => $email,
            'token' => $token,
            'user_reset_password_error' => $userResetPasswordError,
            'stay_signed_in' => $this->getPersistentValue('stay-signed-in'),
            'external_links' => $this->container->getParameter('external_links')

        )), $cacheValidatorHeaders);        
    }
    
}