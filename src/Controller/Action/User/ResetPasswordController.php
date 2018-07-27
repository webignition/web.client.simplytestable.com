<?php

namespace App\Controller\Action\User;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\InvalidCredentialsException;
use App\Services\Mailer;
use App\Services\UserManager;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Postmark\Models\PostmarkException;
use App\Controller\AbstractController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\SimplyTestableUserModel\User;

class ResetPasswordController extends AbstractController
{
    const FLASH_BAG_REQUEST_ERROR_KEY = 'user_reset_password_error';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK = 'blank-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID = 'invalid-user';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS = 'core-app-invalid-credentials';

    const FLASH_BAG_REQUEST_SUCCESS_KEY = 'user_reset_password_confirmation';
    const FLASH_BAG_REQUEST_MESSAGE_SUCCESS = 'token-sent';

    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN = 'postmark-failure';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL = 'invalid-email';

    const FLASH_BAG_RESET_PASSWORD_ERROR_KEY = 'user_reset_password_error';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_PASSWORD_BLANK = 'blank-password';
    const FLASH_BAG_RESET_PASSWORD_ERROR_MESSAGE_FAILED_READ_ONLY = 'failed-read-only';

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        RouterInterface $router,
        UserService $userService,
        Twig_Environment $twig,
        FlashBagInterface $flashBag,
        Mailer $mailer,
        UserManager $userManager
    ) {
        parent::__construct($router);

        $this->userService = $userService;
        $this->twig = $twig;
        $this->flashBag = $flashBag;
        $this->mailer = $mailer;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws InvalidAdminCredentialsException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function requestAction(Request $request)
    {
        $requestData = $request->request;

        $email = trim($requestData->get('email'));

        if (empty($email)) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK
            );

            return new RedirectResponse($this->generateUrl('view_user_reset_password_request'));
        }

        $redirectResponse = new RedirectResponse($this->generateUrl(
            'view_user_reset_password_request',
            [
                'email' => $email
            ]
        ));

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($email, new RFCValidation())) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $redirectResponse;
        }

        try {
            if (!$this->userService->exists($email)) {
                $this->flashBag->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS
            );

            $this->mailer->sendInvalidAdminCredentialsNotification([
                'call' => 'UserService::exists()',
                'args' => [
                    'email' => $email,
                ],
            ]);

            return $redirectResponse;
        }

        $token = $this->userService->getConfirmationToken($email);

        try {
            $this->mailer->sendPasswordResetConfirmationToken($email, $token);
            $this->flashBag->set(
                self::FLASH_BAG_REQUEST_SUCCESS_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (PostmarkException $postmarkException) {
            if (405 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif (300 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL
                );
            } else {
                $this->flashBag->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }
        }

        return $redirectResponse;
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
    public function chooseAction(Request $request)
    {
        $requestData = $request->request;
        $flashBag = $this->flashBag;

        $email = trim($requestData->get('email'));
        $requestToken = trim($requestData->get('token'));
        $staySignedIn = empty($requestData->get('stay-signed-in')) ? 0 : 1;
        $userExists = $this->userService->exists($email);

        $emailValidator = new EmailValidator;
        $isEmailValid = $emailValidator->isValid($email, new RFCValidation());

        if (!$isEmailValid || empty($requestToken) || !$userExists) {
            return new RedirectResponse($this->generateUrl('view_user_reset_password_request'));
        }

        $failureRedirectResponse = new RedirectResponse($this->generateUrl('view_user_reset_password_choose', [
            'email' => $email,
            'token' => $requestToken,
            'stay-signed-in' => $staySignedIn
        ]));

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

        $response = new RedirectResponse($this->generateUrl('view_dashboard'));

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }
}
