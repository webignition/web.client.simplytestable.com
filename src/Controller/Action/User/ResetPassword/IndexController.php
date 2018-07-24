<?php

namespace App\Controller\Action\User\ResetPassword;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use App\Controller\AbstractController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Services\Configuration\MailConfiguration;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class IndexController extends AbstractController
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

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var MailConfiguration
     */
    private $mailConfiguration;

    /**
     * @var PostmarkClient
     */
    private $postmarkClient;

    /**
     * @param RouterInterface $router
     * @param UserService $userService
     * @param Twig_Environment $twig
     * @param SessionInterface $session
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkClient $postmarkClient
     */
    public function __construct(
        RouterInterface $router,
        UserService $userService,
        Twig_Environment $twig,
        SessionInterface $session,
        MailConfiguration $mailConfiguration,
        PostmarkClient $postmarkClient
    ) {
        parent::__construct($router);

        $this->userService = $userService;
        $this->twig = $twig;
        $this->session = $session;
        $this->mailConfiguration = $mailConfiguration;
        $this->postmarkClient = $postmarkClient;
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
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK
            );

            return new RedirectResponse($this->generateUrl('view_user_resetpassword_request'));
        }

        $redirectResponse = new RedirectResponse($this->generateUrl(
            'view_user_resetpassword_request',
            [
                'email' => $email
            ]
        ));

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($email, new RFCValidation())) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $redirectResponse;
        }

        try {
            if (!$this->userService->exists($email)) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS
            );

            $this->sendInvalidAdminCredentialsNotification();

            return $redirectResponse;
        }

        $token = $this->userService->getConfirmationToken($email);

        try {
            $this->sendPasswordResetConfirmationToken($email, $token);
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_SUCCESS_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (PostmarkException $postmarkException) {
            if (405 === $postmarkException->postmarkApiErrorCode) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif (300 === $postmarkException->postmarkApiErrorCode) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL
                );
            } else {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }
        }

        return $redirectResponse;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @throws PostmarkException
     * @throws MailConfigurationException
     */
    private function sendPasswordResetConfirmationToken($email, $token)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_reset_password');

        $confirmationUrl = $this->generateUrl(
            'view_user_resetpassword_choose_index',
            [
                'email' => $email,
                'token' => $token
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $email,
            $messageProperties['subject'],
            null,
            $this->twig->render(
                'Email/reset-password-confirmation.txt.twig',
                [
                    'confirmation_url' => $confirmationUrl,
                    'email' => $email
                ]
            )
        );
    }

    /**
     * @throws PostmarkException
     * @throws MailConfigurationException
     */
    private function sendInvalidAdminCredentialsNotification()
    {
        $sender = $this->mailConfiguration->getSender('default');

        $this->postmarkClient->sendEmail(
            $sender['email'],
            'jon@simplytestable.com',
            'Invalid admin user credentials',
            null,
            'Invalid admin user credentials exception raised when calling UserService::exists()'
        );
    }
}
