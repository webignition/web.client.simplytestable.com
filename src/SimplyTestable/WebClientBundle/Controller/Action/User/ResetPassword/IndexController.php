<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class IndexController extends Controller
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
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws InvalidAdminCredentialsException
     * @throws PostmarkResponseException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function requestAction(Request $request)
    {
        $session = $this->container->get('session');
        $userService = $this->container->get(UserService::class);
        $router = $this->container->get('router');

        $requestData = $request->request;

        $email = trim($requestData->get('email'));

        if (empty($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK
            );

            return new RedirectResponse($router->generate(
                'view_user_resetpassword_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        $redirectResponse = new RedirectResponse($router->generate(
            'view_user_resetpassword_index_index',
            [
                'email' => $email
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($email)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            return $redirectResponse;
        }

        try {
            if (!$userService->exists($email)) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_REQUEST_ERROR_MESSAGE_USER_INVALID
                );

                return $redirectResponse;
            }
        } catch (InvalidAdminCredentialsException $invalidAdminCredentialsException) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_INVALID_ADMIN_CREDENTIALS
            );

            $this->sendInvalidAdminCredentialsNotification();

            return $redirectResponse;
        }

        $token = $userService->getConfirmationToken($email);

        try {
            $this->sendPasswordResetConfirmationToken($email, $token);
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_SUCCESS_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INVALID_EMAIL
                );
            } else {
                $session->getFlashBag()->set(
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
     * @throws PostmarkResponseException
     * @throws MailConfigurationException
     */
    private function sendPasswordResetConfirmationToken($email, $token)
    {
        $mailService = $this->container->get('SimplyTestable\WebClientBundle\Services\Mail\Service');
        $router = $this->container->get('router');

        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_reset_password');

        $confirmationUrl = $router->generate(
            'view_user_resetpassword_choose_index',
            [
                'email' => $email,
                'token' => $token
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $viewName = 'SimplyTestableWebClientBundle:Email:reset-password-confirmation.txt.twig';

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView($viewName, [
            'confirmation_url' => $confirmationUrl,
            'email' => $email
        ]));

        $mailService->getSender()->send($message);
    }

    /**
     * @throws PostmarkResponseException
     * @throws MailConfigurationException
     */
    private function sendInvalidAdminCredentialsNotification()
    {
        $mailService = $this->container->get('SimplyTestable\WebClientBundle\Services\Mail\Service');
        $mailServiceConfiguration = $mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo('jon@simplytestable.com');
        $message->setSubject('Invalid admin user credentials');
        $message->setTextMessage('Invalid admin user credentials exception raised when calling UserService::exists()');

        $mailService->getSender()->send($message);
    }
}
