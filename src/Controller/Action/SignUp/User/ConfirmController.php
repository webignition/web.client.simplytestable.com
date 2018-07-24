<?php

namespace App\Controller\Action\SignUp\User;

use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use App\Controller\AbstractController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\Mail\Configuration\Exception;
use App\Services\Configuration\MailConfiguration;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ConfirmController extends AbstractController
{
    const FLASH_BAG_TOKEN_RESEND_ERROR_KEY = 'token_resend_error';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID = 'invalid-user';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID = 'core-app-invalid-credentials';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_UNKNOWN = 'core-app-unknown-error';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';
    const FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN = 'postmark-failure';

    const FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY = 'token_resend_confirmation';
    const FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE = 'sent';

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
        $this->router = $router;
        $this->session = $session;
        $this->mailConfiguration = $mailConfiguration;
        $this->postmarkClient = $postmarkClient;
    }

    /**
     * @param string $email
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function resendAction($email)
    {
        $redirectResponse = new RedirectResponse($this->generateUrl(
            'view_user_sign_up_confirm_index',
            [
                'email' => $email
            ]
        ));

        try {
            if (!$this->userService->exists($email)) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID
            );

            $this->sendInvalidAdminCredentialsNotification();

            return $redirectResponse;
        }

        $token = $this->userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($email, $token);
        } catch (PostmarkException $postmarkException) {
            if (405 === $postmarkException->postmarkApiErrorCode) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }

            return $redirectResponse;
        }

        $this->session->getFlashBag()->set(
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY,
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE
        );

        return $redirectResponse;
    }

    /**
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    private function sendConfirmationToken($email, $token)
    {
        $sender = $this->mailConfiguration->getSender('default');
        $messageProperties = $this->mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl(
            'view_user_sign_up_confirm_index',
            [
                'email' => $email,
                'token' => $token,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->postmarkClient->sendEmail(
            $sender['email'],
            $email,
            $messageProperties['subject'],
            null,
            $this->twig->render(
                'Email/user-creation-confirmation.txt.twig',
                [
                    'confirmation_url' => $confirmationUrl,
                    'confirmation_code' => $token
                ]
            )
        );
    }

    /**
     * @throws PostmarkException
     * @throws Exception
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
