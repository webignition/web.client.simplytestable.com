<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\User;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;

class ConfirmController extends Controller
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
     * @param string $email
     *
     * @return RedirectResponse
     *
     * @throws PostmarkResponseException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function resendAction($email)
    {
        $userService = $this->container->get(UserService::class);
        $session = $this->container->get('session');
        $mailService = $this->container->get(MailService::class);
        $router = $this->container->get('router');

        $redirectResponse = new RedirectResponse($router->generate(
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
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_CORE_APP_ADMIN_CREDENTIALS_INVALID
            );

            $this->sendInvalidAdminCredentialsNotification($mailService);

            return $redirectResponse;
        }

        $token = $userService->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($router, $mailService, $email, $token);
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
     * @param RouterInterface $router
     * @param MailService $mailService
     * @param string $email
     * @param string $token
     *
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    private function sendConfirmationToken(RouterInterface $router, MailService $mailService, $email, $token)
    {
        $mailConfiguration = $mailService->getConfiguration();

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
     * @throws Exception
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
