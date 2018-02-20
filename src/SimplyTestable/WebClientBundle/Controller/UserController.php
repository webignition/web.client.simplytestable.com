<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAlreadyExistsException;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Services\ResqueQueueService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Model\User;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;
use webignition\ResqueJobFactory\ResqueJobFactory;
use SimplyTestable\WebClientBundle\Services\Mail\Configuration as MailConfiguration;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;

class UserController extends Controller
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

    const FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_KEY = 'user_error';
    const FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_MESSAGE_USER_INVALID = 'invalid-user';

    const FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY = 'user_token_error';
    const FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_BLANK = 'blank-token';
    const FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_FAILED_READ_ONLY = 'failed-read-only';
    const FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';

    /**
     * @return RedirectResponse
     */
    public function signOutSubmitAction()
    {
        $userManager = $this->container->get(UserManager::class);
        $router = $this->container->get('router');

        $userManager->clearSessionUser();

        $response = $this->createDashboardRedirectResponse($router);

        $response->headers->clearCookie(UserManager::USER_COOKIE_KEY, '/', '.simplytestable.com');

        return $response;
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function signInSubmitAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get(UserService::class);
        $router = $this->container->get('router');
        $userManager = $this->container->get(UserManager::class);
        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);

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

        $user = new User($email, $password);

        if (SystemUserService::isPublicUser($user)) {
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

        $coreApplicationHttpClient->setUser($user);

        if (!$userService->authenticate()) {
            if (!$userService->exists()) {
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

            $token = $userService->getConfirmationToken($email);

            $this->sendConfirmationToken($router, $email, $token);

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
            $token = $userService->getConfirmationToken($email);
            $this->sendConfirmationToken($router, $email, $token);

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

        $userManager->setUser($user);

        $response = $this->createPostSignInRedirectResponse($router, $redirect);

        if ($staySignedIn) {
            $response->headers->setCookie($userManager->createUserCookie());
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
            return new RedirectResponse($router->generate(
                $routeName,
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        } catch (\Exception $exception) {
        }

        return $this->createDashboardRedirectResponse($router);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function resetPasswordChooseSubmitAction(Request $request)
    {
        $userService = $this->container->get(UserService::class);
        $router = $this->container->get('router');
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);

        $requestData = $request->request;
        $flashBag = $session->getFlashBag();

        $email = trim($requestData->get('email'));
        $requestToken = trim($requestData->get('token'));
        $staySignedIn = empty($requestData->get('stay-signed-in')) ? 0 : 1;
        $userExists = $userService->exists($email);

        if (!$this->isEmailValid($email) || empty($requestToken) || !$userExists) {
            return new RedirectResponse($router->generate(
                'view_user_resetpassword_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
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

        try {
            $userService->resetPassword($token, $password);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set(
                self::FLASH_BAG_RESET_PASSWORD_ERROR_KEY,
                self::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY
            );

            return $failureRedirectResponse;
        }

        $user = new User($email, $password);
        $userManager->setUser($user);

        $response = $this->createDashboardRedirectResponse($router);

        if ($staySignedIn) {
            $response->headers->setCookie($userManager->createUserCookie());
        }

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function signUpSubmitAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get(UserService::class);
        $couponService = $this->container->get(CouponService::class);
        $router = $this->container->get('router');

        $requestData = $request->request;
        $flashBag = $session->getFlashBag();

        $plan = trim($requestData->get('plan'));
        $email = strtolower(trim($requestData->get('email')));
        $password = trim($requestData->get('password'));

        if (empty($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->createSignUpRedirectResponse($router, [
                'plan' => $plan,
            ]);
        }

        $failureRedirectResponse = $this->createSignUpRedirectResponse($router, [
            'email' => $email,
            'plan' => $plan,
        ]);

        if (!$this->isEmailValid($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $failureRedirectResponse;
        }

        if (empty($password)) {
            $flashBag->set('user_create_prefil', $email);
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_PASSWORD_BLANK
            );

            return $failureRedirectResponse;
        }

        $couponService->setRequest($request);
        $coupon = null;

        if ($couponService->has()) {
            $coupon = $couponService->get();
            if (!$coupon->isActive()) {
                $coupon = null;
            }
        }

        try {
            $userService->create($email, $password, $plan, $coupon);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY
            );

            return $failureRedirectResponse;
        } catch (UserAlreadyExistsException $userAlreadyExistsException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_SUCCESS_KEY,
                self::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS
            );

            return $this->createSignUpRedirectResponse($router, [
                'email' => $email,
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN
            );

            return $failureRedirectResponse;
        }

        $token = $userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($router, $email, $token);
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $flashBag->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $flashBag->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $flashBag->set(
                    self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                    self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_INVALID
                );
            }

            return $failureRedirectResponse;
        }

        $flashBag->set(
            self::FLASH_BAG_SIGN_UP_SUCCESS_KEY,
            self::FLASH_BAG_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED
        );

        $successRedirectUrl = $router->generate(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($successRedirectUrl);
    }

    /**
     * @param RouterInterface $router
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    private function sendConfirmationToken(RouterInterface $router, $email, $token)
    {
        $mailConfiguration = $this->container->get(MailConfiguration::class);
        $mailService = $this->container->get(MailService::class);

        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $router->generate(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
                'token' => $token,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

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

        $mailService->getSender()->send($message);
    }

    /**
     * @param Request $request
     * @param string $email
     *
     * @return RedirectResponse
     *
     * @throws InvalidAdminCredentialsException
     * @throws \CredisException
     * @throws \Exception
     */
    public function signUpConfirmSubmitAction(Request $request, $email)
    {
        $userService = $this->container->get(UserService::class);
        $session = $this->container->get('session');
        $router = $this->container->get('router');
        $resqueQueueService = $this->container->get(ResqueQueueService::class);
        $resqueJobFactory = $this->container->get(ResqueJobFactory::class);

        $userExists = $userService->exists($email);
        $flashBag = $session->getFlashBag();
        $requestData = $request->request;

        $failureRedirect = new RedirectResponse($router->generate(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        if (!$userExists) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_CONFIRM_USER_ERROR_MESSAGE_USER_INVALID
            );

            return $failureRedirect;
        }

        $token = trim($requestData->get('token'));
        if (empty($token)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_BLANK
            );

            return $failureRedirect;
        }

        try {
            $userService->activate($token);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_FAILED_READ_ONLY
            );

            return $failureRedirect;
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_CONFIRM_TOKEN_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $failureRedirect;
        }

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'announcements',
                    'email' => $email,
                ]
            )
        );

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'introduction',
                    'email' => $email,
                ]
            )
        );

        $flashBag->set('user_signin_confirmation', 'user-activated');

        $redirectParameters = [
            'email' => $email
        ];

        $requestRedirectCookie = $request->cookies->get('simplytestable-redirect');
        if (!empty($requestRedirectCookie)) {
            $redirectParameters['redirect'] = $requestRedirectCookie;
        }

        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            $redirectParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
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
     * @param RouterInterface $router
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createSignInRedirectResponse(RouterInterface $router, array $routeParameters)
    {
        return new RedirectResponse($router->generate(
            'view_user_signin_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param RouterInterface $router
     *
     * @return RedirectResponse
     */
    private function createDashboardRedirectResponse(RouterInterface $router)
    {
        return new RedirectResponse($router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param RouterInterface $router
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createPasswordChooseRedirectResponse(RouterInterface $router, array $routeParameters)
    {
        return new RedirectResponse($router->generate(
            'view_user_resetpassword_choose_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param RouterInterface $router
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createSignUpRedirectResponse(RouterInterface $router, array $routeParameters = [])
    {
        return new RedirectResponse($router->generate(
            'view_user_signup_index_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
