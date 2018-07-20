<?php

namespace AppBundle\Controller\Action\User;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use AppBundle\Controller\AbstractController;
use AppBundle\Exception\CoreApplicationReadOnlyException;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidAdminCredentialsException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Exception\UserAlreadyExistsException;
use AppBundle\Request\User\SignInRequest;
use AppBundle\Request\User\SignUpRequest;
use AppBundle\Resque\Job\EmailListSubscribeJob;
use AppBundle\Services\Configuration\MailConfiguration;
use AppBundle\Services\CouponService;
use AppBundle\Services\RedirectResponseFactory;
use AppBundle\Services\Request\Factory\User\SignInRequestFactory;
use AppBundle\Services\Request\Factory\User\SignUpRequestFactory;
use AppBundle\Services\Request\Validator\User\SignInRequestValidator;
use AppBundle\Services\Request\Validator\User\UserAccountRequestValidator;
use AppBundle\Services\ResqueQueueService;
use AppBundle\Services\UserManager;
use AppBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class UserController extends AbstractController
{
    const ONE_YEAR_IN_SECONDS = 31536000;

    const FLASH_BAG_SIGN_IN_ERROR_KEY = 'user_signin_error';

    const FLASH_SIGN_IN_ERROR_FIELD_KEY = 'user_signin_error_field';
    const FLASH_SIGN_IN_ERROR_STATE_KEY = 'user_signin_error_state';
    const FLASH_SIGN_IN_ERROR_STATE_PUBLIC_USER = 'public-user';
    const FLASH_SIGN_IN_ERROR_STATE_INVALID_USER = 'invalid-user';
    const FLASH_SIGN_IN_ERROR_STATE_AUTHENTICATION_FAILURE = 'authentication-failure';
    const FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED = 'user-not-enabled';

    const FLASH_SIGN_UP_ERROR_KEY = 'user_create_error';

    const FLASH_SIGN_UP_ERROR_FIELD_KEY = 'user_signup_error_field';
    const FLASH_SIGN_UP_ERROR_STATE_KEY = 'user_signup_error_state';

    const FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY = 'create-failed-read-only';
    const FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN = 'create-failed';
    const FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';

    const FLASH_SIGN_UP_SUCCESS_KEY = 'user_create_confirmation';
    const FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS = 'user-exists';
    const FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED = 'user-created';

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
     * @var Session
     */
    private $session;

    /**
     * @var RedirectResponseFactory
     */
    private $redirectResponseFactory;

    /**
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param UserService $userService
     * @param SessionInterface $session
     * @param RedirectResponseFactory $redirectResponseFactory
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        UserService $userService,
        SessionInterface $session,
        RedirectResponseFactory $redirectResponseFactory
    ) {
        parent::__construct($router);

        $this->userManager = $userManager;
        $this->userService = $userService;
        $this->router = $router;
        $this->session = $session;
        $this->redirectResponseFactory = $redirectResponseFactory;
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
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     * @param Twig_Environment $twig
     * @param SignInRequestFactory $signInRequestFactory
     * @param SignInRequestValidator $signInRequestValidator
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function signInSubmitAction(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        SignInRequestFactory $signInRequestFactory,
        SignInRequestValidator $signInRequestValidator
    ) {
        $signInRequest = $signInRequestFactory->create();
        $signInRequestValidator->validate($signInRequest);

        $email = $signInRequest->getEmail();
        $password = $signInRequest->getPassword();
        $staySignedIn = $signInRequest->getStaySignedIn();
        $redirect = $signInRequest->getRedirect();

        $flashBag = $this->session->getFlashBag();

        $signInRedirectResponse = $this->redirectResponseFactory->createSignInRedirectResponse($signInRequest);

        if (false === $signInRequestValidator->getIsValid()) {
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, $signInRequestValidator->getInvalidFieldName());
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, $signInRequestValidator->getInvalidFieldState());

            return $signInRedirectResponse;
        }

        $user = new User($email, $password);
        $this->userManager->setUser($user);

        if (!$this->userService->authenticate()) {
            if (!$this->userService->exists()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
                $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_INVALID_USER);

                return $signInRedirectResponse;
            }

            if ($this->userService->isEnabled()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
                $flashBag->set(
                    self::FLASH_SIGN_IN_ERROR_STATE_KEY,
                    self::FLASH_SIGN_IN_ERROR_STATE_AUTHENTICATION_FAILURE
                );

                return $signInRedirectResponse;
            }

            $this->userManager->clearSessionUser();

            $token = $this->userService->getConfirmationToken($email);

            $this->sendConfirmationToken($mailConfiguration, $postmarkClient, $twig, $email, $token);

            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED);

            return $signInRedirectResponse;
        }

        if (!$this->userService->isEnabled()) {
            $this->userManager->clearSessionUser();

            $token = $this->userService->getConfirmationToken($email);
            $this->sendConfirmationToken($mailConfiguration, $postmarkClient, $twig, $email, $token);

            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED);

            return $signInRedirectResponse;
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
            return new RedirectResponse($this->generateUrl($routeName, $routeParameters));
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
            return new RedirectResponse($this->generateUrl('view_user_resetpassword_index_index'));
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
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     * @param CouponService $couponService
     * @param Twig_Environment $twig
     * @param SignUpRequestFactory $signUpRequestFactory
     * @param UserAccountRequestValidator $userAccountRequestValidator
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
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        CouponService $couponService,
        Twig_Environment $twig,
        SignUpRequestFactory $signUpRequestFactory,
        UserAccountRequestValidator $userAccountRequestValidator,
        Request $request
    ) {
        $signUpRequest = $signUpRequestFactory->create();
        $userAccountRequestValidator->validate($signUpRequest);

        $email = $signUpRequest->getEmail();
        $password = $signUpRequest->getPassword();
        $plan = $signUpRequest->getPlan();

        $flashBag = $this->session->getFlashBag();

        $signUpRedirectResponse = $this->redirectResponseFactory->createSignUpRedirectResponse($signUpRequest);

        if (false === $userAccountRequestValidator->getIsValid()) {
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, $userAccountRequestValidator->getInvalidFieldName());
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_STATE_KEY, $userAccountRequestValidator->getInvalidFieldState());

            return $signUpRedirectResponse;
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
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, null);
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_KEY, self::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_READ_ONLY);

            return $signUpRedirectResponse;
        } catch (UserAlreadyExistsException $userAlreadyExistsException) {
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, SignUpRequest::PARAMETER_EMAIL);
            $flashBag->set(self::FLASH_SIGN_UP_SUCCESS_KEY, self::FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS);

            return $signUpRedirectResponse;
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, null);
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_KEY, self::FLASH_SIGN_UP_ERROR_MESSAGE_CREATE_FAILED_UNKNOWN);

            return $signUpRedirectResponse;
        }

        $token = $this->userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($mailConfiguration, $postmarkClient, $twig, $email, $token);
        } catch (PostmarkException $postmarkException) {
            $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, SignUpRequest::PARAMETER_EMAIL);

            if (405 === $postmarkException->postmarkApiErrorCode) {
                $flashBag->set(
                    self::FLASH_SIGN_UP_ERROR_KEY,
                    self::FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $flashBag->set(
                    self::FLASH_SIGN_UP_ERROR_KEY,
                    self::FLASH_SIGN_UP_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $flashBag->set(self::FLASH_SIGN_UP_ERROR_STATE_KEY, UserAccountRequestValidator::STATE_INVALID);
            }

            return $signUpRedirectResponse;
        }

        $flashBag->set(self::FLASH_SIGN_UP_SUCCESS_KEY, self::FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED);

        $successRedirectUrl = $this->generateUrl(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
            ]
        );

        return new RedirectResponse($successRedirectUrl);
    }

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     * @param Twig_Environment $twig
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    private function sendConfirmationToken(
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient,
        Twig_Environment $twig,
        $email,
        $token
    ) {
        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
                'token' => $token,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $postmarkClient->sendEmail(
            $sender['email'],
            $email,
            $messageProperties['subject'],
            null,
            $twig->render(
                'Email/user-creation-confirmation.txt.twig',
                [
                    'confirmation_url' => $confirmationUrl,
                    'confirmation_code' => $token
                ]
            )
        );
    }

    /**
     * @param ResqueQueueService $resqueQueueService
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
        Request $request,
        $email
    ) {
        $userExists = $this->userService->exists($email);
        $flashBag = $this->session->getFlashBag();
        $requestData = $request->request;

        $failureRedirect = new RedirectResponse($this->generateUrl(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
            ]
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

        $resqueQueueService->enqueue(new EmailListSubscribeJob([
            'listId' => 'announcements',
            'email' => $email,
        ]));

        $resqueQueueService->enqueue(new EmailListSubscribeJob([
            'listId' => 'introduction',
            'email' => $email,
        ]));

        $flashBag->set('user_signin_confirmation', 'user-activated');

        $redirectParameters = [
            'email' => $email
        ];

        $requestRedirectCookie = $request->cookies->get('simplytestable-redirect');
        if (!empty($requestRedirectCookie)) {
            $redirectParameters['redirect'] = $requestRedirectCookie;
        }

        return new RedirectResponse($this->generateUrl('view_user_signin_index', $redirectParameters));
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function isEmailValid($email)
    {
        $validator = new EmailValidator();

        return $validator->isValid($email, new RFCValidation());
    }

    /**
     * @return RedirectResponse
     */
    private function createDashboardRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_dashboard_index_index'));
    }

    /**
     * @param array $routeParameters
     *
     * @return RedirectResponse
     */
    private function createPasswordChooseRedirectResponse(array $routeParameters)
    {
        return new RedirectResponse($this->generateUrl(
            'view_user_resetpassword_choose_index',
            $routeParameters
        ));
    }
}