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
        
        $redirectResponse = $this->redirect($this->generateUrl('user_account_index', array(), true));
        
        if (!$this->hasAnyRequestValues()) {
            $this->get('session')->setFlash('user_account_details_update_notice', 'show-help');
        }
        
        if ($this->hasRequestEmailAddress()) {
            if ($this->getRequestEmailAddress() === $this->getUser()->getUsername()) {
                $this->get('session')->setFlash('user_account_details_update_notice', 'same-email');
            } else {                
                if ($this->isEmailValid($this->getRequestEmailAddress())) {
                    $this->getUserService()->setUser($this->getUser());            
                    $response = $this->getUserEmailChangeRequestService()->createEmailChangeRequest($this->getRequestEmailAddress());
                    
                    if ($response === true) {
                        $this->sendEmailChangeConfirmationToken();  
                        $this->get('session')->setFlash('user_account_details_update_notice', 'email-done');
                    } else {
                        switch ($response) {
                            case 409:
                                $this->get('session')->setFlash('user_account_details_update_notice', 'email-taken');
                                $this->get('session')->setFlash('user_account_details_update_email', $this->getRequestEmailAddress());
                                break;

                            default:
                                $this->get('session')->setFlash('user_account_details_update_notice', 'unknown');
                        }                        
                    }          
                } else {
                    $this->get('session')->setFlash('user_account_details_update_notice', 'invalid-email');
                    $this->get('session')->setFlash('user_account_details_update_email', $this->getRequestEmailAddress());
                }                
            }
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
    
    
    public function confirmEmailChangeAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }        
        
        $actionKeys = array('confirm', 're-send', 'cancel');
        $requestedAction = null;
        
        foreach ($actionKeys as $actionKey) {
            if ($this->get('request')->request->has($actionKey)) {
                if (!is_null($requestedAction)) {
                    return $this->redirect($this->generateUrl('user_account_index', array(), true)); 
                }
                
                $requestedAction = $actionKey;
            }            
        }
        
        switch ($requestedAction) {
            case 'confirm':
                return $this->confirmEmailChangeConfirmAction($this->get('request')->request->get('token'));
                
            case 're-send':                
                return $this->confirmEmailChangeResendAction();
                
            case 'cancel':
                return $this->confirmEmailChangeCancelAction();                
        }
        
        return $this->redirect($this->generateUrl('user_account_index', array(), true));
    }
    
    private function confirmEmailChangeConfirmAction($token) {        
        $redirectResponse =  $this->redirect($this->generateUrl('user_account_index', array(), true)); 
        
        $token = trim($token);
        if ($token === '') {
            $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'invalid-token');
            return $redirectResponse;          
        }
        
        $emailChangeRequest = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());        
        if ($token !== $emailChangeRequest['token']) {
            $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'invalid-token');
                        return $redirectResponse; 
        }
        
        $result = $this->getUserEmailChangeRequestService()->confirmEmailChangeRequest($token);
        
        if ($result === true) {   
            $user = $this->getUser();            
            $user->setUsername($emailChangeRequest['new_email']);            
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
            
            $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'success');
        } else {
            switch ($result) {
                case 400:
                    $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'invalid-token');
                    break;
                
                case 409:
                    $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'email-taken');
                    $this->get('session')->setFlash('user_account_details_update_email', $emailChangeRequest['new_email']);
                    break;
                
                default:
                    $this->get('session')->setFlash('user_account_details_update_email_confirm_notice', 'unknown');
                    break;                    
            }
        }
        
        return $redirectResponse; 
    }
    
    private function confirmEmailChangeResendAction() {
        $this->sendEmailChangeConfirmationToken();
        $this->get('session')->setFlash('user_account_details_confirm_email_change_notice', 're-sent');
        return $this->redirect($this->generateUrl('user_account_index', array(), true));
    }
    
    private function confirmEmailChangeCancelAction() {
        $this->getUserEmailChangeRequestService()->cancelEmailChangeRequest();
        $this->get('session')->setFlash('user_account_details_confirm_email_change_notice', 'cancelled');
        return $this->redirect($this->generateUrl('user_account_index', array(), true));
    }
    
    private function sendEmailChangeConfirmationToken() {        
        $emailChangeRequest = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
        
        $userCreationConfirmationEmailSettings = $this->container->getParameter('user_email_change_request_confirmation_email');

        $confirmationUrl = $this->generateUrl('user_account_index', array(), true).'?token=' . $emailChangeRequest['token'];
        
        $message = \Swift_Message::newInstance();
        
        $message->setSubject($userCreationConfirmationEmailSettings['subject']);
        $message->setFrom($userCreationConfirmationEmailSettings['sender_email'], $userCreationConfirmationEmailSettings['sender_name']);
        $message->setTo($emailChangeRequest['new_email']);
        $message->setBody($this->renderView('SimplyTestableWebClientBundle:Email:user-email-change-request-confirmation.txt.twig', array(
            'current_email' => $this->getUser()->getUsername(),
            'new_email' => $emailChangeRequest['new_email'],
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $emailChangeRequest['token']
        )));
        
        $this->get('mailer')->send($message);        
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
        return trim($this->get('request')->request->get('email'));
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
    
    
    /**
     * 
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {        
        if (strpos($email, '@') <= 0) {
            return false;
        }
        
        try {
            $message = \Swift_Message::newInstance();
            $message->setTo($email);            
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }    
}