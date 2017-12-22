<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\User;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ConfirmController extends BaseController
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

    public function resendAction($email)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $session = $this->container->get('session');
        $mailService = $this->container->get('simplytestable.services.mail.service');

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_signup_confirm_index',
            ['email' => $email],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        try {
            if (!$userService->exists($email)) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (CoreApplicationAdminRequestException $coreApplicationAdminRequestException) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID
            );

            $this->sendInvalidAdminCredentialsNotification($mailService);

            return $redirectResponse;
        }

        $token = $userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($mailService, $email, $token);
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } else {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }

            return $redirectResponse;
        }

        $session->getFlashBag()->set(
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_KEY,
            self::FLASH_BAG_TOKEN_RESEND_SUCCESS_MESSAGE
        );

        return $redirectResponse;
    }

    /**
     * @param MailService $mailService
     * @param string $email
     * @param string $token
     *
     * @throws PostmarkResponseException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    private function sendConfirmationToken(MailService $mailService, $email, $token)
    {
        $mailConfiguration = $mailService->getConfiguration();

        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl(
            'view_user_signup_confirm_index',
            [
                'email' => $email
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ) . '?token=' . $token;

        $viewName = 'SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig';

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView($viewName, [
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $token
        ]));

        $mailService->getSender()->send($message);
    }

    /**
     * @param MailService $mailService
     *
     * @throws PostmarkResponseException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    private function sendInvalidAdminCredentialsNotification(MailService $mailService)
    {
        $sender = $mailService->getConfiguration()->getSender('default');

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo('jon@simplytestable.com');
        $message->setSubject('Invalid admin user credentials');
        $message->setTextMessage('Invalid admin user credentials exception raised when calling UserService::exists()');

        $mailService->getSender()->send($message);
    }
}
