<?php

namespace SimplyTestable\WebClientBundle\Controller\User;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use Symfony\Component\HttpFoundation\Cookie;

class ViewController extends BaseViewController implements IEFiltered {     
    const ONE_YEAR_IN_SECONDS = 31536000;    
    
    public function signInAction() {       
        if ($this->isLoggedIn()) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }      
        
        $templateName = 'SimplyTestableWebClientBundle:bs3/User:signin.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $userSignInError = $this->getFlash('user_signin_error');
        $userSignInConfirmation = $this->getFlash('user_signin_confirmation');
        $redirect = $this->getPersistentValue('redirect');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'email' => $this->getPersistentValue('email'),
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $this->getPersistentValue('stay-signed-in')
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);

        return $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'email' => $this->getPersistentValue('email'),
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $this->getPersistentValue('stay-signed-in'),
            'external_links' => $this->container->getParameter('external_links')
        )), $cacheValidatorHeaders);        
    }
    
    
    public function signUpAction()
    {        
        $templateName = 'SimplyTestableWebClientBundle:bs3/User:signup.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);
        
        $userCreateErrors = $this->get('session')->getFlashBag()->get('user_create_error');        
        $userCreateError = (count($userCreateErrors) == 0) ? '' : $userCreateErrors[0];
        
        $userCreateConfirmations = $this->get('session')->getFlashBag()->get('user_create_confirmation');        
        $userCreateConfirmation = (count($userCreateConfirmations) == 0) ? '' : $userCreateConfirmations[0];        

        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $this->getPersistentValue('email')
        ));
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);
        
        $response = $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $this->getPersistentValue('email'),
            'plan' => $this->getPersistentValue('plan'),
            'external_links' => $this->container->getParameter('external_links')
        )), $cacheValidatorHeaders); 
        
        
        
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
    
    
    public function resetPasswordAction() {   
        $userResetPasswordError = $this->getFlash('user_reset_password_error');
        $userResetPasswordConfirmation = $this->getFlash('user_reset_password_confirmation');
        
        $templateName = 'SimplyTestableWebClientBundle:bs3/User:reset-password.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'email' => $this->getPersistentValue('email'),
            'user_password_reset_error' => $userResetPasswordError,
            'user_reset_password_confirmation' => $userResetPasswordConfirmation
        ));      
        
        $cacheValidatorHeaders = $this->getCacheValidatorHeadersService()->get($cacheValidatorIdentifier);
        
//        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
        $cacheValidatorHeaders->setLastModifiedDate($templateLastModifiedDate);
        $this->getCacheValidatorHeadersService()->store($cacheValidatorHeaders);

        return $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'email' => $this->getPersistentValue('email'),
            'user_reset_password_error' => $userResetPasswordError,
            'user_reset_password_confirmation' => $userResetPasswordConfirmation,
            'external_links' => $this->container->getParameter('external_links')

        )), $cacheValidatorHeaders);        
    }  
    
    
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
        
//        if ($this->isProduction() && $cacheValidatorHeaders->getLastModifiedDate() == $templateLastModifiedDate) {            
//            $response = $this->getCachableResponse(new Response(), $cacheValidatorHeaders);            
//            if ($response->isNotModified($this->getRequest())) {
//                return $response;
//            }
//        }
        
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
    
    
    public function signUpConfirmAction() {       
        $email = trim($this->get('request')->get('email'));
        $userError = ($this->getUserService()->exists($email)) ? '' : 'invalid-user';        

        $tokenResendError = $this->getFlash('token_resend_error');
        if ($tokenResendError == 'invalid-user') {
            $tokenResendError = '';
            $userError = 'invalid-user';
        }        

        $notifications = array(
            'token_resend_confirmation' => $this->getFlash('token_resend_confirmation'),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation'),
            'user_token_error' => $this->getFlash('user_token_error'),
            'token_resend_error' => $this->getFlash('token_resend_error'),
            'user_error' => $userError             
        );       
        
        $this->setTemplate('SimplyTestableWebClientBundle:bs3/User:signup-confirm.html.twig');
        
        return $this->sendResponse(array_merge(array(
            'public_site' => $this->container->getParameter('public_site'),
            'external_links' => $this->container->getParameter('external_links'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'token' => $this->get('request')->query->get('token'),            
            'has_notification' => $this->hasNotification($notifications)
        ), $notifications));
    }
    
    
    /**
     * 
     * @param array $notifications
     * @return boolean
     */
    private function hasNotification($notifications) {
        foreach ($notifications as $notification) {
            if (!empty($notification)) {
                return true;
            }
        }
        
        return false;
    }
    
}