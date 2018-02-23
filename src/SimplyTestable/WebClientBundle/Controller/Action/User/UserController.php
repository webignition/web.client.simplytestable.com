<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAlreadyExistsException;
use SimplyTestable\WebClientBundle\Services\CouponService;
use SimplyTestable\WebClientBundle\Services\ResqueQueueService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use SimplyTestable\WebClientBundle\Model\User;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\ResqueJobFactory\ResqueJobFactory;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;

class UserController
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
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param UserManager $userManager
     * @param UserService $userService
     * @param RouterInterface $router
     * @param SessionInterface $session
     */
    public function __construct(
        UserManager $userManager,
        UserService $userService,
        RouterInterface $router,
        SessionInterface $session
    ) {
        $this->userManager = $userManager;
        $this->userService = $userService;
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * @return RedirectResponse
     */
    public function signOutSubmitAction()
    {
        $this->userManager->clearSessionUser();

        $response = $this->createDashboardRedirectResponse();

        $response->headers->clearCookie(UserManager::USER_COOKIE_KEY, '/', '.simplytestable.com');

        return $response;
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    public function signInSubmitAction(MailService $mailService, Twig_Environment $twig, Request $request)
    {
        $requestData = $request->request;

        $email = strtolower(trim($requestData->get('email')));
        $redirect = trim($requestData->get('redirect'));
        $staySignedIn = empty(trim($requestData->get('stay-signed-in'))) ? 0 : 1;
        $password = trim($requestData->get('password'));

        $flashBag = $this->session->getFlashBag();

        if (empty($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->createSignInRedirectResponse([
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        if (!$this->isEmailValid($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $this->createSignInRedirectResponse([
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

            return $this->createSignInRedirectResponse([
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

            return $this->createSignInRedirectResponse([
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        $this->userManager->setUser($user);

        if (!$this->userService->authenticate()) {
            if (!$this->userService->exists()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_INVALID_USER
                );

                return $this->createSignInRedirectResponse([
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn,
                ]);
            }

            if ($this->userService->isEnabled()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(
                    self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                    self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_AUTHENTICATION_FAILURE
                );

                return $this->createSignInRedirectResponse([
                    'email' => $email,
                    'redirect' => $redirect,
                    'stay-signed-in' => $staySignedIn,
                ]);
            }

            $this->userManager->clearSessionUser();

            $token = $this->userService->getConfirmationToken($email);

            $this->sendConfirmationToken($mailService, $twig, $email, $token);

            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->createSignInRedirectResponse([
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        if (!$this->userService->isEnabled()) {
            $this->userManager->clearSessionUser();

            $token = $this->userService->getConfirmationToken($email);
            $this->sendConfirmationToken($mailService, $twig, $email, $token);

            $flashBag->set(
                self::FLASH_BAG_SIGN_IN_ERROR_KEY,
                self::FLASH_BAG_SIGN_IN_ERROR_MESSAGE_USER_NOT_ENABLED
            );

            return $this->createSignInRedirectResponse([
                'email' => $email,
                'redirect' => $redirect,
                'stay-signed-in' => $staySignedIn,
            ]);
        }

        $response = $this->createPostSignInRedirectResponse($redirect);

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }

    /**
     * @param string $requestRedirect
     *
     * @return RedirectResponse
     */
    private function createPostSignInRedirectResponse($requestRedirect)
    {
        $redirectValues = json_decode(base64_decode($requestRedirect), true);

        if (!is_array($redirectValues) || !isset($redirectValues['route'])) {
            return $this->createDashboardRedirectResponse();
        }

        $routeName = $redirectValues['route'];
        $routeParameters = isset($redirectValues['parameters']) ? $redirectValues['parameters'] : [];

        try {
            return new RedirectResponse($this->router->generate(
                $routeName,
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        } catch (\Exception $exception) {
        }

        return $this->createDashboardRedirectResponse();
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
        $requestData = $request->request;
        $flashBag = $this->session->getFlashBag();

        $email = trim($requestData->get('email'));
        $requestToken = trim($requestData->get('token'));
        $staySignedIn = empty($requestData->get('stay-signed-in')) ? 0 : 1;
        $userExists = $this->userService->exists($email);

        if (!$this->isEmailValid($email) || empty($requestToken) || !$userExists) {
            return new RedirectResponse($this->router->generate(
                'view_user_resetpassword_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $failureRedirectResponse = $this->createPasswordChooseRedirectResponse([
            'email' => $email,
            'token' => $requestToken,
            'stay-signed-in' => $staySignedIn
        ]);

        $token = $this->userService->getConfirmationToken($email);

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
            $this->userService->resetPassword($token, $password);
        } catch (CoreApplicationReadOnlyException $coreApplicationReadOnlyException) {
            $flashBag->set(
                self::FLASH_BAG_RESET_PASSWORD_ERROR_KEY,
                self::FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY
            );

            return $failureRedirectResponse;
        }

        $user = new User($email, $password);
        $this->userManager->setUser($user);

        $response = $this->createDashboardRedirectResponse();

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }

    /**
     * @param MailService $mailService
     * @param CouponService $couponService
     * @param Twig_Environment $twig
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function signUpSubmitAction(
        MailService $mailService,
        CouponService $couponService,
        Twig_Environment $twig,
        Request $request
    ) {
        $requestData = $request->request;
        $flashBag = $this->session->getFlashBag();

        $plan = trim($requestData->get('plan'));
        $email = strtolower(trim($requestData->get('email')));
        $password = trim($requestData->get('password'));

        if (empty($email)) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_EMAIL_BLANK
            );

            return $this->createSignUpRedirectResponse([
                'plan' => $plan,
            ]);
        }

        $failureRedirectResponse = $this->createSignUpRedirectResponse([
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
            $this->userService->create($email, $password, $plan, $coupon);
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

            return $this->createSignUpRedirectResponse([
                'email' => $email,
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $flashBag->set(
                self::FLASH_BAG_SIGN_UP_ERROR_KEY,
                self::FLASH_BAG_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN
            );

            return $failureRedirectResponse;
        }

        $token = $this->userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($mailService, $twig, $email, $token);
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

        $successRedirectUrl = $this->router->generate(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($successRedirectUrl);
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    private function sendConfirmationToken(
        MailService $mailService,
        Twig_Environment $twig,
        $email,
        $token
    ) {
        $mailConfiguration = $mailService->getConfiguration();

        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->router->generate(
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
        $message->setTextMessage($twig->render(
            'SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig',
            [
                'confirmation_url' => $confirmationUrl,
                'confirmation_code' => $token
            ]
        ));

        $mailService->getSender()->send($message);
    }

    /**
     * @param ResqueQueueService $resqueQueueService
     * @param ResqueJobFactory $resqueJobFactory
     * @param Request $request
     * @param string $email
     *
     * @return RedirectResponse
     *
     * @throws InvalidAdminCredentialsException
     * @throws \CredisException
     * @throws \Exception
     */
    public function signUpConfirmSubmitAction(
        ResqueQueueService $resqueQueueService,
        ResqueJobFactory $resqueJobFactory,
        Request $request,
        $email
    ) {
        $userExists = $this->userService->exists($email);
        $flashBag = $this->session->getFlashBag();
        $requestData = $request->request;

        $failureRedirect = new RedirectResponse($this->router->generate(
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
            $this->userService->activate($token);
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

        return new RedirectResponse($this->router->generate(
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
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createSignInRedirectResponse(array $routeParameters)
    {
        return new RedirectResponse($this->router->generate(
            'view_user_signin_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @return RedirectResponse
     */
    private function createDashboardRedirectResponse()
    {
        return new RedirectResponse($this->router->generate(
            'view_dashboard_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createPasswordChooseRedirectResponse(array $routeParameters)
    {
        return new RedirectResponse($this->router->generate(
            'view_user_resetpassword_choose_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createSignUpRedirectResponse(array $routeParameters = [])
    {
        return new RedirectResponse($this->router->generate(
            'view_user_signup_index_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
