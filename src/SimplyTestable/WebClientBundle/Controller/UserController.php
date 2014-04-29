<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\HttpFoundation\Cookie;
use Egulias\EmailValidator\EmailValidator;

class UserController extends BaseViewController
{     
    const ONE_YEAR_IN_SECONDS = 31536000;
    
    public function signOutSubmitAction() {
        $this->getUserService()->clearUser();
        
        $response = $this->redirect($this->generateUrl('app', array(), true));         
        $response->headers->clearCookie('simplytestable-user', '/', '.simplytestable.com');
        
        return $response;    
    }    
    
    public function signInSubmitAction() {
        $email = trim($this->get('request')->request->get('email')); 
        $redirect = trim($this->get('request')->request->get('redirect')); 
        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1; 

        if ($email == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-email');
            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));             
        } 
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_signin_error', 'invalid-email');
            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));              
        }        
        
        $password = trim($this->get('request')->request->get('password'));        

        if ($password == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-password');
            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));             
        }          
        
        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        
        if ($this->getUserService()->isPublicUser($user)) {
            $this->get('session')->setFlash('user_signin_error', 'public-user');
            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));             
        }
        
        $this->getUserService()->setUser($user);
        
        if (!$this->getUserService()->authenticate()) {
            if (!$this->getUserService()->exists()) {
                $this->getUserService()->clearUser();
                $this->get('session')->setFlash('user_signin_error', 'invalid-user');
                return $this->redirect($this->generateUrl('user_view_signin_index', array(
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ), true));                 
            }           

            if ($this->getUserService()->isEnabled()) {
                $this->getUserService()->clearUser();
                $this->get('session')->setFlash('user_signin_error', 'authentication-failure');
                return $this->redirect($this->generateUrl('user_view_signin_index', array(
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ), true));                        
            }
            
            $this->getUserService()->clearUser();
            $token = $this->getUserService()->getConfirmationToken($email);

            $this->sendConfirmationToken($email, $token);                  

            $this->get('session')->setFlash('user_signin_error', 'user-not-enabled');          

            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true)); 
        }
        
        if (!$this->getUserService()->isEnabled()) {
            $this->getUserService()->clearUser();
            $token = $this->getUserService()->getConfirmationToken($email);        
            $this->sendConfirmationToken($email, $token);                  

            $this->get('session')->setFlash('user_signin_error', 'user-not-enabled');
            return $this->redirect($this->generateUrl('user_view_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));                  
        }        
        
        $this->getUserService()->setUser($user);
        
        $response = $this->getPostSignInRedirectResponse();        
        
        if ($staySignedIn == "1") {
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
            
            $response->headers->setCookie($cookie);
        }        
        
        return $response;
    }
    
    
    private function getPostSignInRedirectResponse() {
        $redirectValues = json_decode(base64_decode($this->get('request')->request->get('redirect')), true);        
      
        if (!is_array($redirectValues) || !isset($redirectValues['route'])) {
            return $this->redirect($this->generateUrl('app', array(), true));
        }

        $parameters = isset($redirectValues['parameters']) ? $redirectValues['parameters'] : array();
        return $this->redirect($this->generateUrl($redirectValues['route'], $parameters, true));
    } 
    
    
    public function resetPasswordAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
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
    
    
    public function resetPasswordChooseAction($email, $token)
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }    
        
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
    

    public function resetPasswordSubmitAction() {                
        $email = trim($this->get('request')->request->get('email'));        

        if ($email == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-email');
            return $this->redirect($this->generateUrl('user_view_resetpassword', array(), true));             
        }                
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('user_view_resetpassword', array(
                'email' => $email
            ), true));              
        }
        
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-user');
            return $this->redirect($this->generateUrl('user_view_resetpassword', array('email' => $email), true));          
        }    
        
        $token = $this->getUserService()->getConfirmationToken($email);
        try {
            $this->sendPasswordResetConfirmationToken($email, $token);            
            $this->get('session')->setFlash('user_reset_password_confirmation', 'token-sent');
            return $this->redirect($this->generateUrl('user_view_resetpassword', array('email' => $email), true));           
        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('user_view_resetpassword', array(
                'email' => $email
            ), true));             
        }
    }  
    
    
    public function resetPasswordChooseSubmitAction() {
        $email = trim($this->get('request')->request->get('email'));        
        $inputToken = trim($this->get('request')->request->get('token'));
        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1; 

        if (!$this->isEmailValid($email) || $inputToken == '' || $this->getUserService()->exists($email) === false) {
            return $this->redirect($this->generateUrl('user_view_resetpassword', array(), true));             
        }                

        $token = $this->getUserService()->getConfirmationToken($email);             
        
        if ($token != $inputToken) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('user_view_resetpasswordchoose', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));            
        }
        
        $password = trim($this->get('request')->request->get('password'));
        
        if ($password == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-password');
            return $this->redirect($this->generateUrl('user_view_resetpasswordchoose', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));               
        }
        
        $passwordResetResponse = $this->getUserService()->resetPassword($token, $password);        
        
        if ($this->requestFailedDueToReadOnly($passwordResetResponse)) {
            $this->get('session')->setFlash('user_reset_password_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('user_view_resetpasswordchoose', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));             
        }
        
        if ($passwordResetResponse === 404) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('user_view_resetpasswordchoose', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));            
        }
        
        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        $this->getUserService()->setUser($user);
        
        $response = $this->redirect($this->generateUrl('app', array(), true));
        
        if ($staySignedIn == "1") {
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
            
            $response->headers->setCookie($cookie);
        }        
        
        return $response;        
    }        
    
    public function signUpSubmitAction() {
        $plan = trim($this->get('request')->request->get('plan'));
        
        $email = trim($this->get('request')->request->get('email'));        
        if ($email == '') {
            $this->get('session')->setFlash('user_create_error', 'blank-email');
            return $this->redirect($this->generateUrl('user_view_signup', array(
                'plan' => $plan 
            ), true));             
        }
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_create_error', 'invalid-email');
            return $this->redirect($this->generateUrl('user_view_signup', array(
                'email' => $email,
                'plan' => $plan 
            ), true));              
        } 
        
        $password = trim($this->get('request')->request->get('password'));
        if ($password == '') {
            $this->get('session')->setFlash('user_create_prefil', $email);
            $this->get('session')->setFlash('user_create_error', 'blank-password');
            return $this->redirect($this->generateUrl('user_view_signup', array(
                'email' => $email,
                'plan' => $plan 
            ), true));               
        }        
        
        $this->getUserService()->setUser($this->getUserService()->getPublicUser());
        $createResponse = $this->getUserService()->create($email, $password, $plan);        
        
        if ($this->userCreationUserAlreadyExists($createResponse)) {
            $this->get('session')->setFlash('user_create_confirmation', 'user-exists');
            return $this->redirect($this->generateUrl('user_view_signup', array('email' => $email), true));             
        }
        
        if ($this->requestFailedDueToReadOnly($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed-read-only');
            return $this->redirect($this->generateUrl('user_view_signup', array('email' => $email), true));               
        }        
                
        if ($this->userCreationFailed($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed');
            return $this->redirect($this->generateUrl('user_view_signup', array('email' => $email), true));               
        }
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        
        try {
            $this->sendConfirmationToken($email, $token);         
            $this->get('session')->setFlash('user_create_confirmation', 'user-created');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));            
        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            $this->get('session')->setFlash('user_create_error', 'invalid-email');
            return $this->redirect($this->generateUrl('user_view_signup', array(
                'email' => $email,
                'plan' => $plan 
            ), true));
        }
    }
    
    
    public function signUpConfirmAction()
    {        
        if ($this->isUsingOldIE()) {
            return $this->forward('SimplyTestableWebClientBundle:App:outdatedBrowser');
        }
        
        
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
    
    
    public function signupConfirmResendAction($email) {
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('token_resend_error', 'invalid-user');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));          
        }
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        
        try {
            $this->sendConfirmationToken($email, $token);        
            $this->get('session')->setFlash('token_resend_confirmation', 'sent');      

            return new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl('user_view_signupconfirm', array(
                'email' => $email
            )));    
        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            $this->get('session')->setFlash('token_resend_error', 'postmark-failure');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));  
        }
    }
    
    
    /**
     * 
     * @param string $email
     * @param  $token
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendConfirmationToken($email, $token) {        
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_creation_confirmation');        

        $confirmationUrl = $this->generateUrl('user_view_signupconfirm', array(
            'email' => $email
        ), true).'?token=' . $token;       
        
        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $token
        )));
        
        $this->getMailService()->getSender()->send($message); 
    } 
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }
    
    
    private function sendPasswordResetConfirmationToken($email, $token) {        
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_reset_password');
  
        $confirmationUrl = $this->generateUrl('user_view_resetpasswordchoose', array(
            'email' => $email,
            'token' => $token
        ), true);
        
        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:reset-password-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'email' => $email
        )));
        
        $this->getMailService()->getSender()->send($message);     
    }    
    
    
    public function signupConfirmSubmitAction($email) {       
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('token_resend_error', 'invalid-user');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));          
        }
        
        $token = trim($this->get('request')->get('token'));
        if ($token == '') {
            $this->get('session')->setFlash('user_token_error', 'blank-token');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));            
        }
        
        $activationResponse = $this->getUserService()->activate($token);        
        if ($this->requestFailedDueToReadOnly($activationResponse)) {
            $this->get('session')->setFlash('user_token_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));                
        }
        
        if ($activationResponse == false) {
            $this->get('session')->setFlash('user_token_error', 'invalid-token');
            return $this->redirect($this->generateUrl('user_view_signupconfirm', array('email' => $email), true));            
        }
        
        $this->getResqueQueueService()->add(
            'SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob',
            'email-list-subscribe',
            array(
                'listId' => 'announcements',
                'email' => $email,
            )
        );
        
        $this->get('session')->setFlash('user_signin_confirmation', 'user-activated');
        
        $redirectParameters = array(
            'email' => $email
        );
        
        if (!is_null($this->get('request')->cookies->get('simplytestable-redirect'))) {
            $redirectParameters['redirect'] = $this->get('request')->cookies->get('simplytestable-redirect');
        }
        
        return $this->redirect($this->generateUrl('user_view_signin_index', $redirectParameters, true));  
    }    

    /**
     * 
     * @param mixed $createResponse
     * @return boolean
     */
    private function requestFailedDueToReadOnly($responseCode) {
        return $responseCode === 503;
    }    
    
    
    /**
     * 
     * @param mixed $createResponse
     * @return boolean
     */
    private function userCreationFailed($createResponse) {
        return $createResponse !== true;
    }
    
    
    /**
     * 
     * @param boolean $createResponse
     * @return boolean
     */
    private function userCreationUserAlreadyExists($createResponse) {
        return $createResponse === 302;
    }  
    
    
    /**
     * 
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {        
        $validator = new EmailValidator;
        return $validator->isValid($email);
    }
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\ResqueQueueService
     */        
    private function getResqueQueueService() {
        return $this->container->get('simplytestable.services.resqueQueueService');
    }      
}