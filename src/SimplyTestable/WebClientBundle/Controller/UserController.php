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
        
        $response = $this->redirect($this->generateUrl('view_dashboard_index_index', array(), true));
        $response->headers->clearCookie('simplytestable-user', '/', '.simplytestable.com');
        
        return $response;    
    }    
    
    public function signInSubmitAction() {
        $email = strtolower(trim($this->get('request')->request->get('email')));
        $redirect = trim($this->get('request')->request->get('redirect')); 
        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1; 

        if ($email == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-email');
            return $this->redirect($this->generateUrl('view_user_signin_index', array(
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));             
        } 
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_signin_error', 'invalid-email');
            return $this->redirect($this->generateUrl('view_user_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));              
        }        
        
        $password = trim($this->get('request')->request->get('password'));        

        if ($password == '') {
            $this->get('session')->setFlash('user_signin_error', 'blank-password');
            return $this->redirect($this->generateUrl('view_user_signin_index', array(
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
            return $this->redirect($this->generateUrl('view_user_signin_index', array(
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
                return $this->redirect($this->generateUrl('view_user_signin_index', array(
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ), true));                 
            }           

            if ($this->getUserService()->isEnabled()) {
                $this->getUserService()->clearUser();
                $this->get('session')->setFlash('user_signin_error', 'authentication-failure');
                return $this->redirect($this->generateUrl('view_user_signin_index', array(
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ), true));                        
            }
            
            $this->getUserService()->clearUser();
            $token = $this->getUserService()->getConfirmationToken($email);

            $this->sendConfirmationToken($email, $token);                  

            $this->get('session')->setFlash('user_signin_error', 'user-not-enabled');          

            return $this->redirect($this->generateUrl('view_user_signin_index', array(
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
            return $this->redirect($this->generateUrl('view_user_signin_index', array(
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
            return $this->redirect($this->generateUrl('view_dashboard_index_index', array(), true));
        }

        $parameters = isset($redirectValues['parameters']) ? $redirectValues['parameters'] : array();
        return $this->redirect($this->generateUrl($redirectValues['route'], $parameters, true));
    }   
    

    public function resetPasswordSubmitAction() {                
        $email = trim($this->get('request')->request->get('email'));        

        if ($email == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-email');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(), true));
        }                
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(
                'email' => $email
            ), true));              
        }
        
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-user');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array('email' => $email), true));
        }    
        
        $token = $this->getUserService()->getConfirmationToken($email);
        try {
            $this->sendPasswordResetConfirmationToken($email, $token);            
            $this->get('session')->setFlash('user_reset_password_confirmation', 'token-sent');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array('email' => $email), true));
        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(
                'email' => $email
            ), true));             
        }
    }  
    
    
    public function resetPasswordChooseSubmitAction() {
        $email = trim($this->get('request')->request->get('email'));        
        $inputToken = trim($this->get('request')->request->get('token'));
        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1; 

        if (!$this->isEmailValid($email) || $inputToken == '' || $this->getUserService()->exists($email) === false) {
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(), true));
        }                

        $token = $this->getUserService()->getConfirmationToken($email);             
        
        if ($token != $inputToken) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));            
        }
        
        $password = trim($this->get('request')->request->get('password'));
        
        if ($password == '') {
            $this->get('session')->setFlash('user_reset_password_error', 'blank-password');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));               
        }
        
        $passwordResetResponse = $this->getUserService()->resetPassword($token, $password);        
        
        if ($this->requestFailedDueToReadOnly($passwordResetResponse)) {
            $this->get('session')->setFlash('user_reset_password_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));             
        }
        
        if ($passwordResetResponse === 404) {
            $this->get('session')->setFlash('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));            
        }
        
        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        $this->getUserService()->setUser($user);
        
        $response = $this->redirect($this->generateUrl('view_dashboard_index_index', array(), true));
        
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
        
        $email = strtolower(trim($this->get('request')->request->get('email')));
        if ($email == '') {
            $this->get('session')->setFlash('user_create_error', 'blank-email');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array(
                'plan' => $plan 
            ), true));             
        }
        
        if (!$this->isEmailValid($email)) {
            $this->get('session')->setFlash('user_create_error', 'invalid-email');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array(
                'email' => $email,
                'plan' => $plan 
            ), true));              
        } 
        
        $password = trim($this->get('request')->request->get('password'));
        if ($password == '') {
            $this->get('session')->setFlash('user_create_prefil', $email);
            $this->get('session')->setFlash('user_create_error', 'blank-password');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array(
                'email' => $email,
                'plan' => $plan 
            ), true));               
        }

        $coupon = null;

        if ($this->getCouponService()->has()) {
            $coupon = $this->getCouponService()->get();
            if (!$coupon->isActive()) {
                $coupon = null;
            }
        }

        $this->getUserService()->setUser($this->getUserService()->getPublicUser());
        $createResponse = $this->getUserService()->create($email, $password, $plan, $coupon);

        if ($this->userCreationUserAlreadyExists($createResponse)) {
            $this->get('session')->setFlash('user_create_confirmation', 'user-exists');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array('email' => $email), true));
        }
        
        if ($this->requestFailedDueToReadOnly($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed-read-only');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array('email' => $email), true));
        }        
                
        if ($this->userCreationFailed($createResponse)) {
            $this->get('session')->setFlash('user_create_error', 'create-failed');
            return $this->redirect($this->generateUrl('view_user_signup_index_index', array('email' => $email), true));
        }
        
        $token = $this->getUserService()->getConfirmationToken($email);        
        
        try {
            $this->sendConfirmationToken($email, $token);         
            $this->get('session')->setFlash('user_create_confirmation', 'user-created');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        } catch (\SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->get('session')->setFlash('user_create_error', 'postmark-not-allowed-to-send');
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->get('session')->setFlash('user_create_error', 'postmark-inactive-recipient');
            } else {
                $this->get('session')->setFlash('user_create_error', 'invalid-email');
            }

            return $this->redirect($this->generateUrl('view_user_signup_index_index', array(
                'email' => $email,
                'plan' => $plan 
            ), true));
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

        $confirmationUrl = $this->generateUrl('view_user_signup_confirm_index', array(
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
  
        $confirmationUrl = $this->generateUrl('view_user_resetpassword_choose_index', array(
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
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }
        
        $token = trim($this->get('request')->get('token'));
        if ($token == '') {
            $this->get('session')->setFlash('user_token_error', 'blank-token');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        if ($this->getRequest()->request->has('password')) {
            $password = trim($this->get('request')->get('password'));
            if ($password == '') {
                $this->get('session')->setFlash('user_activate_error', 'blank-password');
                return $this->redirect(
                    $this->generateUrl('view_user_signup_confirm_index', [
                        'email' => $email,
                        'token' => $token
                    ], true)
                );
            }
        } else {
            $password = null;
        }
        
        $activationResponse = $this->getUserService()->activate($token, $password);
        if ($this->requestFailedDueToReadOnly($activationResponse)) {
            $this->get('session')->setFlash('user_token_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }
        
        if ($activationResponse == false) {
            $this->get('session')->setFlash('user_token_error', 'invalid-token');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        $this->getResqueQueueService()->add(
            'SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob',
            'email-list-subscribe',
            array(
                'listId' => 'announcements',
                'email' => $email,
            )
        );

        $this->getResqueQueueService()->add(
            'SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob',
            'email-list-subscribe',
            array(
                'listId' => 'introduction',
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
        
        return $this->redirect($this->generateUrl('view_user_signin_index', $redirectParameters, true));
    }    

    /**
     * 
     * @param mixed $responseCode
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


    /**
     * @return \SimplyTestable\WebClientBundle\Services\CouponService
     */
    private function getCouponService() {
        return $this->container->get('simplytestable.services.couponService');
    }
}