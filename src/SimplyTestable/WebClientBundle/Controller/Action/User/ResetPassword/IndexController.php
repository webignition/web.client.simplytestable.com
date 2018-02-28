<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
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
     * @param UserService $userService
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param RouterInterface $router
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
        $this->session = $session;
    }

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
        $requestData = $request->request;

        $email = trim($requestData->get('email'));

        if (empty($email)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_ERROR_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_BLANK
            );

            return new RedirectResponse($this->generateUrl('view_user_resetpassword_index_index'));
        }

        $redirectResponse = new RedirectResponse($this->generateUrl(
            'view_user_resetpassword_index_index',
            [
                'email' => $email
            ]
        ));

        $emailValidator = new EmailValidator;
        if (!$emailValidator->isValid($email)) {
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
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
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
     * @throws PostmarkResponseException
     * @throws MailConfigurationException
     */
    private function sendPasswordResetConfirmationToken($email, $token)
    {
        $mailServiceConfiguration = $this->mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_reset_password');

        $confirmationUrl = $this->generateUrl(
            'view_user_resetpassword_choose_index',
            [
                'email' => $email,
                'token' => $token
            ]
        );

        $viewName = 'SimplyTestableWebClientBundle:Email:reset-password-confirmation.txt.twig';

        $message = $this->mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->twig->render($viewName, [
            'confirmation_url' => $confirmationUrl,
            'email' => $email
        ]));

        $this->mailService->getSender()->send($message);
    }

    /**
     * @throws PostmarkResponseException
     * @throws MailConfigurationException
     */
    private function sendInvalidAdminCredentialsNotification()
    {
        $mailServiceConfiguration = $this->mailService->getConfiguration();

        $sender = $mailServiceConfiguration->getSender('default');

        $message = $this->mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo('jon@simplytestable.com');
        $message->setSubject('Invalid admin user credentials');
        $message->setTextMessage('Invalid admin user credentials exception raised when calling UserService::exists()');

        $this->mailService->getSender()->send($message);
    }
}
