<?php

namespace App\Controller\Action\User;

use App\Services\Mailer;
use Postmark\Models\PostmarkException;
use App\Controller\AbstractController;
use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\UserAlreadyExistsException;
use App\Request\User\SignUpRequest;
use App\Resque\Job\EmailListSubscribeJob;
use App\Services\CouponService;
use App\Services\RedirectResponseFactory;
use App\Services\Request\Factory\User\SignUpRequestFactory;
use App\Services\Request\Validator\User\UserAccountRequestValidator;
use App\Services\ResqueQueueService;
use App\Services\UserManager;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;
use webignition\SfsClient\Client as SfsClient;
use webignition\SfsResultAnalyser\Analyser as SfsResultAnalyser;
use webignition\SfsResultInterfaces\ResultInterface;

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
    const FLASH_SIGN_UP_ERROR_MESSAGE_UNTRUSTWORTHY_IP = 'untrustworthy-ip';

    const FLASH_SIGN_UP_SUCCESS_KEY = 'user_create_confirmation';
    const FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_EXISTS = 'user-exists';
    const FLASH_SIGN_UP_SUCCESS_MESSAGE_USER_CREATED = 'user-created';

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
     * @var RedirectResponseFactory
     */
    private $redirectResponseFactory;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        UserService $userService,
        RedirectResponseFactory $redirectResponseFactory,
        FlashBagInterface $flashBag
    ) {
        parent::__construct($router);

        $this->userManager = $userManager;
        $this->userService = $userService;
        $this->router = $router;
        $this->redirectResponseFactory = $redirectResponseFactory;
        $this->flashBag = $flashBag;
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
     * @param Mailer $mailer
     * @param CouponService $couponService
     * @param SignUpRequestFactory $signUpRequestFactory
     * @param UserAccountRequestValidator $userAccountRequestValidator
     * @param Request $request
     * @param SfsClient $sfsClient
     * @param SfsResultAnalyser $sfsResultAnalyser
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function signUpSubmitAction(
        Mailer $mailer,
        CouponService $couponService,
        SignUpRequestFactory $signUpRequestFactory,
        UserAccountRequestValidator $userAccountRequestValidator,
        Request $request,
        SfsClient $sfsClient,
        SfsResultAnalyser $sfsResultAnalyser
    ) {
        $honeypotValue = $request->request->get('hp');
        if (null !== $honeypotValue && !empty($honeypotValue) && (bool) $honeypotValue === true) {
            return new RedirectResponse($this->router->generate('view_user_sign_up_request'));
        }

        $signUpRequest = $signUpRequestFactory->create();
        $userAccountRequestValidator->validate($signUpRequest);

        $email = $signUpRequest->getEmail();
        $password = $signUpRequest->getPassword();
        $plan = $signUpRequest->getPlan();

        $signUpRedirectResponse = $this->redirectResponseFactory->createSignUpRedirectResponse($signUpRequest);

        $flashBag = $this->flashBag;

        $clientIp = $request->getClientIp();
        if (is_string($clientIp)) {
            $sfsResult = $sfsClient->queryIp($request->getClientIp());

            if ($sfsResult instanceof ResultInterface && $sfsResultAnalyser->isUntrustworthy($sfsResult)) {
                $flashBag->set(self::FLASH_SIGN_UP_ERROR_FIELD_KEY, $clientIp);
                $flashBag->set(self::FLASH_SIGN_UP_ERROR_KEY, self::FLASH_SIGN_UP_ERROR_MESSAGE_UNTRUSTWORTHY_IP);

                return $signUpRedirectResponse;
            }
        }

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
            $mailer->sendSignUpConfirmationToken($email, $token);
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
            'view_user_sign_up_confirm',
            [
                'email' => $email,
            ]
        );

        return new RedirectResponse($successRedirectUrl);
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
        $flashBag = $this->flashBag;
        $requestData = $request->request;

        $failureRedirect = new RedirectResponse($this->generateUrl(
            'view_user_sign_up_confirm',
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

        return new RedirectResponse($this->generateUrl('sign_in_render', $redirectParameters));
    }

    /**
     * @return RedirectResponse
     */
    private function createDashboardRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_dashboard'));
    }
}
