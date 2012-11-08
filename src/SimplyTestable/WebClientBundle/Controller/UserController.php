<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\User;

class UserController extends BaseViewController
{     
    public function signOutSubmitAction() {
        $this->getUserService()->clearUser();
        return $this->redirect($this->generateUrl('app', array(), true));        
    }
    
    
    public function signInAction()
    {                
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $templateName = 'SimplyTestableWebClientBundle:User:signin.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $userSignInError = $this->getFlash('user_signin_error');
        $userSignInConfirmation = $this->getFlash('user_signin_confirmation');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'email' => $this->getPersistentValue('email'),
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation
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
            'user_signin_confirmation' => $userSignInConfirmation
        )), $cacheValidatorHeaders);        
    }
    
    
    public function signInSubmitAction() {        
        $email = trim($this->get('request')->request->get('email'));        

        if ($email == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-email');
            return $this->redirect($this->generateUrl('sign_in', array(), true));             
        } 
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_signin_error', 'invalid-email');
            return $this->redirect($this->generateUrl('sign_in', array(
                'email' => $email
            ), true));              
        }        
        
        $password = trim($this->get('request')->request->get('password'));        

        if ($password == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-password');
            return $this->redirect($this->generateUrl('sign_in', array('email' => $email), true));             
        }          
        
        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        
        if ($this->getUserService()->isPublicUser($user)) {
            $this->get('session')->setFlash('user_signin_error', 'public-user');
            return $this->redirect($this->generateUrl('sign_in', array('email' => $email), true));             
        }
        
        $this->getUserService()->setUser($user);
        
        if (!$this->getUserService()->authenticate()) {
            $this->getUserService()->clearUser();
            
            if ($this->getUserService()->exists($email)) {
                $token = $this->getUserService()->getConfirmationToken($email);        
                $this->sendConfirmationToken($email, $token);                  
                
                $this->get('session')->setFlash('user_signin_error', 'user-not-enabled');
                return $this->redirect($this->generateUrl('sign_in', array('email' => $email), true));          
            } 
            
            $this->get('session')->setFlash('user_signin_error', 'invalid-user');
            return $this->redirect($this->generateUrl('sign_in', array('email' => $email), true));                            
        }
        
        $this->getUserService()->setUser($user);
        
        return $this->redirect($this->generateUrl('app', array(), true));
    }
    
    
    public function resetPasswordAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        $userResetPasswordError = $this->getFlash('user_reset_password_error');
        $userResetPasswordConfirmation = $this->getFlash('user_reset_password_confirmation');
        
        $templateName = 'SimplyTestableWebClientBundle:User:reset-password.html.twig';
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
            'user_reset_password_confirmation' => $userResetPasswordConfirmation

        )), $cacheValidatorHeaders);        
    }  
    
    
    public function resetPasswordChooseAction($email, $token)
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        //$userResetPasswordError = $this->getFlash('user_reset_password_error');
        //$userResetPasswordConfirmation = $this->getFlash('user_reset_password_confirmation');
        
        $templateName = 'SimplyTestableWebClientBundle:User:reset-password-choose.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        $userResetPasswordError = $this->getFlash('user_reset_password_error');
       
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier(array(
            'email' => $email,
            'user_password_reset_error' => $userResetPasswordError
            //'user_reset_password_confirmation' => $userResetPasswordConfirmation
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
            'user_reset_password_error' => $userResetPasswordError
            //'user_reset_password_confirmation' => $userResetPasswordConfirmation

        )), $cacheValidatorHeaders);        
    }     
    

    public function resetPasswordSubmitAction() {        
        $email = trim($this->get('request')->request->get('email'));        

        if ($email == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-email');
            return $this->redirect($this->generateUrl('reset_password', array(), true));             
        }                
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('reset_password', array(
                'email' => $email
            ), true));              
        }
        
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-user');
            return $this->redirect($this->generateUrl('reset_password', array('email' => $email), true));          
        }    
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        $this->sendPasswordResetConfirmationToken($email, $token);               
        
        $this->get('session')->setFlash('user_reset_password_confirmation', 'token-sent');
        return $this->redirect($this->generateUrl('reset_password', array('email' => $email), true));
    }  
    
    
    public function resetPasswordChooseSubmitAction() {
        $email = trim($this->get('request')->request->get('email'));        
        $inputToken = trim($this->get('request')->request->get('token'));

        if (!$this->isEmailValid($email) || $inputToken == '' || $this->getUserService()->exists($email) === false) {
            return $this->redirect($this->generateUrl('reset_password', array(), true));             
        }                

        $token = $this->getUserService()->getConfirmationToken($email);             
        
        if ($token != $inputToken) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('reset_password_choose', array(
                'email' => $email,
                'token' => $inputToken
            ), true));            
        }
        
        $password = trim($this->get('request')->request->get('password'));
        
        if ($password == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-password');
            return $this->redirect($this->generateUrl('reset_password_choose', array(
                'email' => $email,
                'token' => $inputToken
            ), true));               
        }
        
        $passwordResetResponse = $this->getUserService()->resetPassword($token, $password);
            
        if (is_null($passwordResetResponse)) {
            $this->get('session')->setFlash('user_reset_password_error', 'unknown-error');
            return $this->redirect($this->generateUrl('reset_password_choose', array(
                'email' => $email,
                'token' => $inputToken
            ), true));            
        }
        
        if ($passwordResetResponse === false) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('reset_password_choose', array(
                'email' => $email,
                'token' => $inputToken
            ), true));            
        }
        
        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        
        $this->getUserService()->setUser($user);
        
        return $this->redirect($this->generateUrl('app', array(), true));
    }     
    

    public function signUpAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $templateName = 'SimplyTestableWebClientBundle:User:signup.html.twig';
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

        return $this->getCachableResponse($this->render($templateName, array(            
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'email' => $this->getPersistentValue('email')
        )), $cacheValidatorHeaders);        
    }
    
    
    public function signUpSubmitAction() {
        $email = trim($this->get('request')->request->get('email'));
        
        if ($email == '') {
            $this->get('session')->setFlash('user_create_error', 'blank-email');
            return $this->redirect($this->generateUrl('sign_up', array(), true));             
        }
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_create_error', 'invalid-email');
            return $this->redirect($this->generateUrl('sign_up', array('email' => $email), true));              
        } 
        
        $password = trim($this->get('request')->request->get('password'));
        if ($password == '') {
            $this->get('session')->setFlash('user_create_prefil', $email);
            $this->get('session')->setFlash('user_create_error', 'blank-password');
            return $this->redirect($this->generateUrl('sign_up', array('email' => $email), true));             
        }        
        
        $this->getUserService()->setUser($this->getUserService()->getPublicUser());
        $createResponse = $this->getUserService()->create($email, $password);        
                
        if ($this->userCreationFailed($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed');
            return $this->redirect($this->generateUrl('sign_up', array('email' => $email), true));               
        }
        
        if ($this->userCreationUserAlreadyExists($createResponse)) {
            $this->get('session')->setFlash('user_create_confirmation', 'user-exists');
            return $this->redirect($this->generateUrl('sign_up', array('email' => $email), true));             
        }
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        $this->sendConfirmationToken($email, $token);         
        
        $this->get('session')->setFlash('user_create_confirmation', 'user-created');
        return $this->redirect($this->generateUrl('sign_up_confirm', array('email' => $email), true));
    }
    
    
    public function signUpConfirmAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }       
        
        $this->setTemplate('SimplyTestableWebClientBundle:User:signup-confirm.html.twig');
        return $this->sendResponse(array(
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'token_resend_confirmation' => $this->getFlash('token_resend_confirmation'),
            'user_create_confirmation' => $this->getFlash('user_create_confirmation'),
            'user_token_error' => $this->getFlash('user_token_error'),
            'token_resend_error' => $this->getFlash('token_resend_error'),
            'token' => $this->get('request')->query->get('token')
        ));
    }
    
    
    public function signupConfirmResendAction($email) {        
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('token_resend_error', 'invalid-user');
            return $this->redirect($this->generateUrl('sign_up_confirm', array('email' => $email), true));          
        }             
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        $this->sendConfirmationToken($email, $token);        
        $this->get('session')->setFlash('token_resend_confirmation', 'sent');      
        
        return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('sign_up_confirm', array(
            'email' => $email
        )));
    }
    
    private function sendConfirmationToken($email, $token) {
        $userCreationConfirmationEmailSettings = $this->container->getParameter('user_creation_confirmation_email');        

        $confirmationUrl = $this->generateUrl('sign_up_confirm', array(
                'email' => $email
            ), true).'?token=' . $token;
        
        $message = \Swift_Message::newInstance();
        
        $message->setSubject($userCreationConfirmationEmailSettings['subject']);
        $message->setFrom($userCreationConfirmationEmailSettings['sender_email'], $userCreationConfirmationEmailSettings['sender_name']);
        $message->setTo($email);
        $message->setBody($this->renderView('SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $token
        )));
        
        $this->get('mailer')->send($message);        
    }
    
    
    private function sendPasswordResetConfirmationToken($email, $token) {
        $userPasswordResetEmailSettings = $this->container->getParameter('user_reset_password_email');        

        $confirmationUrl = $this->generateUrl('reset_password_choose', array(
                'email' => $email,
                'token' => $token
            ), true);
        
        $message = \Swift_Message::newInstance();
        
        $message->setSubject($userPasswordResetEmailSettings['subject']);
        $message->setFrom($userPasswordResetEmailSettings['sender_email'], $userPasswordResetEmailSettings['sender_name']);
        $message->setTo($email);
        $message->setBody($this->renderView('SimplyTestableWebClientBundle:Email:reset-password-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'email' => $email
        )));
        
        $this->get('mailer')->send($message);        
    }    
    
    
    public function signupConfirmSubmitAction($email) {
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('token_resend_error', 'invalid-user');
            return $this->redirect($this->generateUrl('sign_up_confirm', array('email' => $email), true));          
        }
        
        $token = trim($this->get('request')->get('token'));
        if ($token == '') {
            $this->get('session')->setFlash('user_token_error', 'blank-token');
            return $this->redirect($this->generateUrl('sign_up_confirm', array('email' => $email), true));            
        }
        
        $activationResponse = $this->getUserService()->activate($token);
        
        if ($activationResponse == false) {
            $this->get('session')->setFlash('user_token_error', 'invalid-token');
            return $this->redirect($this->generateUrl('sign_up_confirm', array('email' => $email), true));            
        }
        
        $this->get('session')->setFlash('user_signin_confirmation', 'user-activated');
        return $this->redirect($this->generateUrl('sign_in', array('email' => $email), true));  
    }    
    
    
    /**
     * 
     * @param mixed $createResponse
     * @return boolean
     */
    private function userCreationFailed($createResponse) {
        return is_null($createResponse);
    }
    
    
    /**
     * 
     * @param boolean $createResponse
     * @return boolean
     */
    private function userCreationUserAlreadyExists($createResponse) {
        return $createResponse === false;
    }  
    
    
    /**
     * 
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {        
        return strpos($email, '@') > 0;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserSerializerService
     */
    private function getUserSerializerService() {
        return $this->container->get('simplytestable.services.userserializerservice');
    }

}
