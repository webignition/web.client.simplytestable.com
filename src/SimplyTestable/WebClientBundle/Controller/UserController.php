<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseViewController
{ 

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
        
        $userCreatePrefils = $this->get('session')->getFlashBag()->get('user_create_prefil');        
        $userCreateEmail = (count($userCreatePrefils) == 0) ? '' : $userCreatePrefils[0];
        
//        return is_array($flashMessages) && count($flashMessages) > 0;        
//        
//        $hasSignupError = $this->hasFlash('user_create_error');
        
        //$hasTestStartError = $this->hasFlash('test_start_error');
        //$hasTestStartBlockedWebsiteError = $this->hasFlash('test_start_error_blocked_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        $cacheValidatorIdentifier->setParameter('user_create_error', $userCreateError);
        //$cacheValidatorIdentifier->setParameter('test_start_error_blocked_website', ($hasTestStartBlockedWebsiteError) ? 'true' : 'false');
        
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
            'test_input_action_url' => $this->generateUrl('test_start'),
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser()),
            'user_create_error' => $userCreateError,
            'user_create_confirmation' => $userCreateConfirmation,
            'user_create_email' => $userCreateEmail
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
            $this->get('session')->setFlash('user_create_prefil', $email);
            return $this->redirect($this->generateUrl('sign_up', array(), true));              
        }       
        
        $this->getUserService()->setUser($this->getUserService()->getPublicUser());
        $createResponse = $this->getUserService()->create($email);
        
        if ($this->userCreationFailed($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed');
            $this->get('session')->setFlash('user_create_prefil', $email);
            return $this->redirect($this->generateUrl('sign_up', array(), true));               
        }
        
        if ($this->userCreationUserAlreadyExists($createResponse)) {
            $this->get('session')->setFlash('user_create_confirmation', 'user-exists');
            $this->get('session')->setFlash('user_create_prefil', $email);
            return $this->redirect($this->generateUrl('sign_up', array(), true));             
        }
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        $this->sendConfirmationToken($email, $token);         
        
        $this->get('session')->setFlash('user_create_confirmation', 'user-created');
        $this->get('session')->setFlash('user_create_prefil', $email);
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
        
        
        
        // redirect to ???
        
        // log user in
        // redirect to app
        
        //return $this->redirect($this->generateUrl('app', array('email' => $email), true));
        
        // redirect to ?
        
        //var_dump($email, $this->get('request')->get('token'));
        exit();
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
        return strpos($email, '@') > 1;
    }
    
    public function signInAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }        
        
        $templateName = 'SimplyTestableWebClientBundle:User:index.html.twig';
        $templateLastModifiedDate = $this->getTemplateLastModifiedDate($templateName);        
        
        //$hasTestStartError = $this->hasFlash('test_start_error');
        //$hasTestStartBlockedWebsiteError = $this->hasFlash('test_start_error_blocked_website');
        
        $cacheValidatorIdentifier = $this->getCacheValidatorIdentifier();
        //$cacheValidatorIdentifier->setParameter('test_start_error', ($hasTestStartError) ? 'true' : 'false');
        //$cacheValidatorIdentifier->setParameter('test_start_error_blocked_website', ($hasTestStartBlockedWebsiteError) ? 'true' : 'false');
        
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
            'test_input_action_url' => $this->generateUrl('test_start'),
            //'test_start_error' => $hasTestStartError,
            //'test_start_error_blocked_website' => $hasTestStartBlockedWebsiteError,
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'is_logged_in' => !$this->getUserService()->isPublicUser($this->getUser())
        )), $cacheValidatorHeaders);        
    }

}
