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
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class UserController extends BaseViewController
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    const FLASH_BAG_SIGN_IN_ERROR_KEY = 'user_signin_error';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_BLANK = 'blank-email';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PUBLIC_USER = 'public-user';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER = 'invalid-user';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_AUTHENTICATION_FAILURE = 'authentication-failure';
    const FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED = 'user-not-enabled';

    const FLASH_BAG_SIGN_UP_ERROR_KEY = 'user_create_error';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_BLANK = 'blank-email';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY = 'create-failed-read-only';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN = 'create-failed';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';

    const FLASH_BAG_SIGN_UP_SUCCESS_KEY = 'user_create_confirmation';
    const FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS = 'user-exists';
    const FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED = 'user-created';

    public function signOutSubmitAction() {
        $this->getUserService()->clearUser();

        $response = $this->redirect($this->generateUrl('view_dashboard_index_index', array(), true));
        $response->headers->clearCookie('simplytestable-user', '/', '.simplytestable.com');

        return $response;
    }

    public function signInSubmitAction()
    {
        $request = $this->container->get('request');
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');

        $requestData = $request->request;

        $email = strtolower(trim($requestData->get('email')));
        $redirect = trim($requestData->get('redirect'));
        $staySignedIn = empty(trim($requestData->get('stay-signed-in'))) ? 0 : 1;
        $password = trim($requestData->get('password'));

        if (empty($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', [
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ], true));
        }

        if (!$this->isEmailValid($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ], true));
        }

        if (empty($password)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ], true));
        }

        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);

        if ($userService->isPublicUser($user)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PUBLIC_USER
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ], true));
        }

        $userService->setUser($user);

        if (!$userService->authenticate()) {
            if (!$userService->exists()) {
                $userService->clearUser();
                $session->getFlashBag()->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER
                );

                return $this->redirect($this->generateUrl('view_user_signin_index', [
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ], true));
            }

            if ($userService->isEnabled()) {
                $userService->clearUser();
                $session->getFlashBag()->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_AUTHENTICATION_FAILURE
                );

                return $this->redirect($this->generateUrl('view_user_signin_index', [
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn
                ], true));
            }

            $userService->clearUser();
            $token = $userService->getConfirmationToken($email);

            $this->sendConfirmationToken($email, $token);

            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));
        }

        if (!$userService->isEnabled()) {
            $userService->clearUser();
            $token = $userService->getConfirmationToken($email);
            $this->sendConfirmationToken($email, $token);

            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->redirect($this->generateUrl('view_user_signin_index', array(
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn
            ), true));
        }

        $userService->setUser($user);

        $response = $this->getPostSignInRedirectResponse();

        if ($staySignedIn) {
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


    public function resetPasswordChooseSubmitAction() {
        $email = trim($this->get('request')->request->get('email'));
        $inputToken = trim($this->get('request')->request->get('token'));
        $staySignedIn = trim($this->get('request')->request->get('stay-signed-in')) == '' ? 0 : 1;

        if (!$this->isEmailValid($email) || $inputToken == '' || $this->getUserService()->exists($email) === false) {
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(), true));
        }

        $token = $this->getUserService()->getConfirmationToken($email);

        if ($token != $inputToken) {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'invalid-token');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));
        }

        $password = trim($this->get('request')->request->get('password'));

        if ($password == '') {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'blank-password');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));
        }

        $passwordResetResponse = $this->getUserService()->resetPassword($token, $password);

        if ($this->requestFailedDueToReadOnly($passwordResetResponse)) {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('view_user_resetpassword_choose_index', array(
                'email' => $email,
                'token' => $inputToken,
                'stay-signed-in' => $staySignedIn
            ), true));
        }

        if ($passwordResetResponse === 404) {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'invalid-token');
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

    public function signUpSubmitAction()
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $couponService = $this->container->get('simplytestable.services.couponservice');

        $request = $this->container->get('request');
        $requestData = $request->request;

        $plan = trim($requestData->get('plan'));
        $email = strtolower(trim($requestData->get('email')));
        $password = trim($requestData->get('password'));

        if (empty($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'plan' => $plan,
            ], true));
        }

        if (!$this->isEmailValid($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'email' => $email,
                'plan' => $plan,
            ], true));
        }

        if (empty($password)) {
            $session->getFlashBag()->set('user_create_prefil', $email);

            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'email' => $email,
                'plan' => $plan,
            ], true));
        }

        $couponService->setRequest($request);
        $coupon = null;

        if ($couponService->has()) {
            $coupon = $couponService->get();
            if (!$coupon->isActive()) {
                $coupon = null;
            }
        }

        $userService->setUser($userService->getPublicUser());
        $createResponse = $userService->create($email, $password, $plan, $coupon);

        if ($this->userCreationUserAlreadyExists($createResponse)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_SUCCESS_KEY,
                self::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', ['email' => $email], true));
        }

        if ($this->requestFailedDueToReadOnly($createResponse)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'email' => $email,
                'plan' => $plan,
            ], true));
        }

        if ($this->userCreationFailed($createResponse)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN
            );

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'email' => $email,
                'plan' => $plan,
            ], true));
        }

        $token = $userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($email, $token);
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID
                );
            }

            return $this->redirect($this->generateUrl('view_user_signup_index_index', [
                'email' => $email,
                'plan' => $plan
            ], true));
        }

        $session->getFlashBag()->set(
            self::FLASH_BAG_SIGN_UP_SUCCESS_KEY,
            self::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED
        );

        return $this->redirect($this->generateUrl('view_user_signup_confirm_index', ['email' => $email], true));
    }

    /**
     * @param string $email
     * @param string  $token
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendConfirmationToken($email, $token)
    {
        $mailConfiguration = $this->container->get('simplytestable.services.mail.configuration');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl('view_user_signup_confirm_index', array(
            'email' => $email
        ), true).'?token=' . $token;

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView(
            'SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig',
            [
                'confirmation_url' => $confirmationUrl,
                'confirmation_code' => $token
            ]
        ));

        $this->getMailService()->getSender()->send($message);
    }

    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }

    public function signupConfirmSubmitAction($email) {
        if ($this->getUserService()->exists($email) === false) {
            $this->get('session')->getFlashBag()->set('token_resend_error', 'invalid-user');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        $token = trim($this->get('request')->get('token'));
        if ($token == '') {
            $this->get('session')->getFlashBag()->set('user_token_error', 'blank-token');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        if ($this->getRequest()->request->has('password')) {
            $password = trim($this->get('request')->get('password'));
            if ($password == '') {
                $this->get('session')->getFlashBag()->set('user_activate_error', 'blank-password');
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
            $this->get('session')->getFlashBag()->set('user_token_error', 'failed-read-only');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        if ($activationResponse == false) {
            $this->get('session')->getFlashBag()->set('user_token_error', 'invalid-token');
            return $this->redirect($this->generateUrl('view_user_signup_confirm_index', array('email' => $email), true));
        }

        $this->getResqueQueueService()->enqueue(
            $this->getResqueJobFactoryService()->create(
              'email-list-subscribe',
                array(
                    'listId' => 'announcements',
                    'email' => $email,
                )
            )
        );


        $this->getResqueQueueService()->enqueue(
            $this->getResqueJobFactoryService()->create(
                'email-list-subscribe',
                array(
                    'listId' => 'introduction',
                    'email' => $email,
                )
            )
        );

        $this->get('session')->getFlashBag()->set('user_signin_confirmation', 'user-activated');

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
     * @return \SimplyTestable\WebClientBundle\Services\Resque\QueueService
     */
    private function getResqueQueueService() {
        return $this->container->get('simplytestable.services.resque.queueService');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Resque\JobFactoryService
     */
    private function getResqueJobFactoryService() {
        return $this->container->get('simplytestable.services.resque.jobFactoryService');
    }
}