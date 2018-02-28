<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\User;

use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
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
     * @var MailService
     */
    private $mailService;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param RouterInterface $router
     * @param UserService $userService
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        UserService $userService,
        MailService $mailService,
        Twig_Environment $twig,
        SessionInterface $session
    ) {
        parent::__construct($router);

        $this->userService = $userService;
        $this->mailService = $mailService;
        $this->twig = $twig;
        $this->router = $router;
        $this->session = $session;
    }

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
        $redirectResponse = new RedirectResponse($this->generateUrl(
            'view_user_signup_confirm_index',
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
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_KEY,
                    self::FLASH_BAG_TOKEN_RESEND_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
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
     * @throws PostmarkResponseException
     */
    private function sendConfirmationToken($email, $token)
    {
        $mailConfiguration = $this->mailService->getConfiguration();

        $sender = $mailConfiguration->getSender('default');
        $messageProperties = $mailConfiguration->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl(
            'view_user_signup_confirm_index',
            [
                'email' => $email,
                'token' => $token,
            ]
        );

        $viewName = 'SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig';

        $message = $this->mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->twig->render($viewName, [
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $token
        ]));

        $this->mailService->getSender()->send($message);
    }

    /**
     * @throws PostmarkResponseException
     * @throws Exception
     */
    private function sendInvalidAdminCredentialsNotification()
    {
        $sender = $this->mailService->getConfiguration()->getSender('default');

        $message = $this->mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo('jon@simplytestable.com');
        $message->setSubject('Invalid admin user credentials');
        $message->setTextMessage('Invalid admin user credentials exception raised when calling UserService::exists()');

        $this->mailService->getSender()->send($message);
    }
}
