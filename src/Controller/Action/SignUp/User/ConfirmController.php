<?php

namespace App\Controller\Action\SignUp\User;

use App\Services\Mailer;
use Postmark\Models\PostmarkException;
use App\Controller\AbstractController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;

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
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var Mailer
     */
    private $mailer;

    public function __construct(
        RouterInterface $router,
        UserService $userService,
        FlashBagInterface $flashBag,
        Mailer $mailer
    ) {
        parent::__construct($router);

        $this->userService = $userService;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->mailer = $mailer;
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
            'view_user_sign_up_confirm',
            [
                'email' => $email
            ]
        ));

        try {
            if (!$this->userService->exists($email)) {
                $this->flashBag->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $this->flashBag->set(
                self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID
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
            $this->mailer->sendSignUpConfirmationToken($email, $token);
        } catch (PostmarkException $postmarkException) {
            if (405 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif (406 === $postmarkException->postmarkApiErrorCode) {
                $this->flashBag->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $this->flashBag->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }

            return $redirectResponse;
        }

        $this->flashBag->set(
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY,
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE
        );

        return $redirectResponse;
    }
}
