<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class UserAccountDetailsController extends AbstractUserAccountController
{   
    const ONE_YEAR_IN_SECONDS = 31536000;    
    
    public function updateAction() {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $redirectResponse = $this->redirect($this->generateUrl('view_user_account_index_index', array(), true));
        
        if (!$this->hasAnyRequestValues()) {
            $this->get('session')->setFlash('user_account_details_update_notice', 'show-help');
        }

        if ($this->hasRequestCurrentPassword() && !$this->hasRequestNewPassword()) {
            $this->get('session')->setFlash('user_account_details_update_notice', 'password-missing');
        } elseif (!$this->hasRequestCurrentPassword() && $this->hasRequestNewPassword()) {
            $this->get('session')->setFlash('user_account_details_update_notice', 'password-missing');
        } elseif ($this->hasRequestCurrentPassword() && $this->hasRequestNewPassword()) {
            if ($this->getRequestCurrentPassword() != $this->getUser()->getPassword()) {
                $this->get('session')->setFlash('user_account_details_update_notice', 'password-invalid');
            } else {
                $this->getUserService()->setUser($this->getUser());
                $passwordResetResponse = $this->getUserService()->resetLoggedInUserPassword($this->getRequestNewPassword());
                
                if ($passwordResetResponse === true) {
                    $user = $this->getUser();            
                    $user->setPassword($this->getRequestNewPassword());
                    $this->getUserService()->setUser($user, true);

                    if (!is_null($this->getRequest()->cookies->get('simplytestable-user'))) {
                        $stringifiedUser = $this->getUserSerializerService()->serializeToString($user);

                        $cookie = new Cookie(
                            'simplytestable-user',
                            $stringifiedUser,
                            time() + self::ONE_YEAR_IN_SECONDS,
                            '/',
                            '.simplytestable.com',
                            false,
                            true
                        );

                        $redirectResponse->headers->setCookie($cookie);
                    }
                    
                    $this->get('session')->setFlash('user_account_details_update_notice', 'password-done');                    
                    
                } else {
                    switch ($passwordResetResponse) {
                        case 503:
                            $this->get('session')->setFlash('user_account_details_update_notice', 'password-failed-read-only');
                            break;
                        
                        default:
                            $this->get('session')->setFlash('user_account_details_update_notice', 'unknown');
                    }                    
                }              
            }
        }
                
        
        return $redirectResponse;        
    }

    
    /**
     * 
     * @return boolean
     */
    private function hasAnyRequestValues() {
        if ($this->hasRequestEmailAddress()) {
            return true;
        }
        
        if ($this->hasRequestCurrentPassword()) {
            return true;
        }
        
        if ($this->hasRequestNewPassword()) {
            return true;
        }
        
        return false;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasRequestEmailAddress() {
        if (!$this->hasRequestValue('email')) {
            return false;
        }
        
        return trim($this->getRequestEmailAddress()) !== '';
    }  
    
    
    /**
     * 
     * @return boolean
     */    
    private function hasRequestCurrentPassword() {
        return $this->hasRequestValue('current-password');
    }
    
    
    /**
     * 
     * @return boolean
     */    
    private function hasRequestNewPassword() {
        return $this->hasRequestValue('new-password');
    }    
    
    
    /**
     * 
     * @return string
     */
    private function getRequestEmailAddress() {        
        return strtolower(trim($this->get('request')->request->get('email')));
    }
    
    
    private function getRequestCurrentPassword() {
        return $this->get('request')->request->get('current-password');
    }
    
    private function getRequestNewPassword() {
        return $this->get('request')->request->get('new-password');
    }    
    
    
    /**
     * 
     * @param string $key
     * @return boolean
     */
    private function hasRequestValue($key) {
        if (!$this->get('request')->request->has($key)) {
            return false;
        }
        
        return $this->get('request')->request->get($key) !== '';          
    }

}