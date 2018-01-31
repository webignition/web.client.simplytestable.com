<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\User;
use Symfony\Component\HttpFoundation\Cookie;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;

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

    const FLASH_BAG_RESET_PASSWORD_ERROR_KEY = 'user_reset_password_error';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY = 'failed-read-only';

    /**
     * @return RedirectResponse
     */
    public function signOutSubmitAction()
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');

        $userService->clearUser();

        $response = $this->createDashboardRedirectResponse($router);

        $response->headers->clearCookie(UserService::USER_COOKIE_KEY, '/', '.simplytestable.com');

        return $response;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws PostmarkResponseException
     * @throws CoreApplicationAdminRequestException
     * @throws MailConfigurationException
     */
    public function signInSubmitAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');

        $requestData = $request->request;

        $email = strtolower(trim($requestData->get('email')));
        $redirect = trim($requestData->get('redirect'));
        $staySignedIn = empty(trim($requestData->get('stay-signed-in'))) ? 0 : 1;
        $password = trim($requestData->get('password'));

        $flashBag = $session->getFlashBag();

        if (empty($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->createSignInRedirectResponse($router, [
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        if (!$this->isEmailValid($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $this->createSignInRedirectResponse($router, [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        if (empty($password)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return $this->createSignInRedirectResponse($router, [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);

        if ($userService->isPublicUser($user)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_PUBLIC_USER
            );

            return $this->createSignInRedirectResponse($router, [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        $userService->setUser($user);

        if (!$userService->authenticate()) {
            if (!$userService->exists()) {
                $userService->clearUser();
                $flashBag->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER
                );

                return $this->createSignInRedirectResponse($router, [
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn,
                ]);
            }

            if ($userService->isEnabled()) {
                $userService->clearUser();
                $flashBag->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_AUTHENTICATION_FAILURE
                );

                return $this->createSignInRedirectResponse($router, [
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn,
                ]);
            }

            $userService->clearUser();
            $token = $userService->getConfirmationToken($email);

            $this->sendConfirmationToken($email, $token);

            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->createSignInRedirectResponse($router, [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        if (!$userService->isEnabled()) {
            $userService->clearUser();
            $token = $userService->getConfirmationToken($email);
            $this->sendConfirmationToken($email, $token);

            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->createSignInRedirectResponse($router, [
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        $userService->setUser($user);

        $response = $this->createPostSignInRedirectResponse($router, $redirect);

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

    /**
     * @param RouterInterface $router
     * @param string $requestRedirect
     *
     * @return RedirectResponse
     */
    private function createPostSignInRedirectResponse(RouterInterface $router, $requestRedirect)
    {
        $redirectValues = json_decode(base64_decode($requestRedirect), true);

        if (!is_array($redirectValues) || !isset($redirectValues['route'])) {
            return $this->createDashboardRedirectResponse($router);
        }

        $routeName = $redirectValues['route'];
        $routeParameters = isset($redirectValues['parameters']) ? $redirectValues['parameters'] : [];

        try {
            $redirectUrl = $router->generate(
                $routeName,
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        } catch (\Exception $exception) {
        }

        return $this->createDashboardRedirectResponse($router);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function resetPasswordChooseSubmitAction(Request $request)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');
        $session = $this->container->get('session');

        $requestData = $request->request;
        $flashBag = $session->getFlashBag();

        $email = trim($requestData->get('email'));
        $requestToken = trim($requestData->get('token'));
        $staySignedIn = empty($requestData->get('stay-signed-in')) ? 0 : 1;
        $userExists = $userService->exists($email);

        if (!$this->isEmailValid($email) || empty($requestToken) || !$userExists) {
            $redirectUrl = $router->generate(
                'view_user_resetpassword_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $failureRedirectResponse = $this->createPasswordChooseRedirectResponse($router, [
            'email' => $email,
            'token' => $requestToken,
            'stay-signed-in' => $staySignedIn
        ]);

        $token = $userService->getConfirmationToken($email);

        if ($token !== $requestToken) {
            $flashBag->set(
                self::FLASH_BAG_RESET_PASSWORD_ERROR_KEY,
                self::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $failureRedirectResponse;
        }

        $password = trim($requestData->get('password'));

        if (empty($password)) {
            $flashBag->set(
                self::FLASH_BAG_RESET_PASSWORD_ERROR_KEY,
                self::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return $failureRedirectResponse;
        }

        $passwordResetResponse = $this->getUserService()->resetPassword($token, $password);

        if ($this->requestFailedDueToReadOnly($passwordResetResponse)) {
            $flashBag->set(
                self::FLASH_BAG_RESET_PASSWORD_ERROR_KEY,
                self::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY
            );

            return $failureRedirectResponse;
        }

        $user = new User();
        $user->setUsername($email);
        $user->setPassword($password);
        $userService->setUser($user);

        $response = $this->createDashboardRedirectResponse($router);

        if ($staySignedIn) {
            $serializedUser = $this->getUserSerializerService()->serializeToString($user);

            $cookie = new Cookie(
                UserService::USER_COOKIE_KEY,
                $serializedUser,
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
     * @param string $email
     *
     * @return bool
     */
    private function isEmailValid($email)
    {
        $validator = new EmailValidator();

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

    /**
     * @param RouterInterface $router
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createSignInRedirectResponse(RouterInterface $router, array $routeParameters)
    {
        $redirectUrl = $router->generate(
            'view_user_signin_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param RouterInterface $router
     *
     * @return RedirectResponse
     */
    private function createDashboardRedirectResponse(RouterInterface $router)
    {
        $redirectUrl = $router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param RouterInterface $router
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createPasswordChooseRedirectResponse(RouterInterface $router, array $routeParameters = [])
    {
        $redirectUrl = $router->generate(
            'view_user_resetpassword_choose_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }
}
