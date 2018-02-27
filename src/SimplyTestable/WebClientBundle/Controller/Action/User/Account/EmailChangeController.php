<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Exception\UserEmailChangeException;
use SimplyTestable\WebClientBundle\Services\ResqueQueueService;
use SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\ResqueJobFactory\ResqueJobFactory;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;

class EmailChangeController extends AbstractUserAccountController
{
    const FLASH_BAG_CONFIRM_KEY = 'user_account_details_update_email_confirm_notice';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN = 'email-taken';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN = 'unknown';
    const FLASH_BAG_CONFIRM_MESSAGE_SUCCESS = 'success';

    const FLASH_BAG_EMAIL_VALUE_KEY = 'user_account_details_update_email';

    const FLASH_BAG_REQUEST_KEY = 'user_account_details_update_email_request_notice';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY = 'blank-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';
    const FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME = 'same-email';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN = 'email-taken';
    const FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN = 'unknown';

    const FLASH_BAG_REQUEST_MESSAGE_SUCCESS = 'email-done';

    const FLASH_BAG_RESEND_SUCCESS_KEY = 'user_account_details_resend_email_change_notice';
    const FLASH_BAG_RESEND_ERROR_KEY = 'user_account_details_resend_email_change_error';
    const FLASH_BAG_RESEND_MESSAGE_SUCCESS = 're-sent';
    const FLASH_BAG_RESEND_ERROR_MESSAGE_EMAIL_INVALID = 'invalid-email';

    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND = 'postmark-not-allowed-to-send';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT = 'postmark-inactive-recipient';
    const FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN = 'postmark-failure';

    /**
     * @var UserEmailChangeRequestService
     */
    private $emailChangeRequestService;

    /**
     * @param RouterInterface $router
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param UserEmailChangeRequestService $emailChangeRequestService
     */
    public function __construct(
        RouterInterface $router,
        UserManager $userManager,
        SessionInterface $session,
        UserEmailChangeRequestService $emailChangeRequestService
    ) {
        parent::__construct($router, $userManager, $session);

        $this->emailChangeRequestService = $emailChangeRequestService;
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function requestAction(MailService $mailService, Twig_Environment $twig, Request $request)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse = new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $newEmail = strtolower(trim($requestData->get('email')));

        if (empty($newEmail)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY
            );

            return $redirectResponse;
        }

        if ($newEmail === $username) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME
            );

            return $redirectResponse;
        }

        $emailValidator = new EmailValidator;
        $emailValidator->isValid($newEmail);

        if (!$emailValidator->isValid($newEmail)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );

            return $redirectResponse;
        }

        try {
            $this->emailChangeRequestService->createEmailChangeRequest($newEmail);
            $this->sendEmailChangeConfirmationToken($mailService, $twig);

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN;
            } else {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN;
            }

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                $flashMessage
            );

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        } catch (PostmarkResponseException $postmarkResponseException) {
            $this->emailChangeRequestService->cancelEmailChangeRequest();

            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND;
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT;
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID;
            } else {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN;
            }

            $this->session->getFlashBag()->set(self::FLASH_BAG_REQUEST_KEY, $flashMessage);

            $this->session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        }

        return $redirectResponse;
    }

    /**
     * @param MailService $mailService
     * @param Twig_Environment $twig
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function resendAction(MailService $mailService, Twig_Environment $twig)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        try {
            $this->sendEmailChangeConfirmationToken($mailService, $twig);
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_RESEND_SUCCESS_KEY,
                self::FLASH_BAG_RESEND_MESSAGE_SUCCESS
            );
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_RESEND_ERROR_MESSAGE_EMAIL_INVALID
                );
            } else {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }
        }

        return new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param ResqueQueueService $resqueQueueService
     * @param ResqueJobFactory $resqueJobFactory
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws \CredisException
     */
    public function confirmAction(
        ResqueQueueService $resqueQueueService,
        ResqueJobFactory $resqueJobFactory,
        Request $request
    ) {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $requestData = $request->request;

        $redirectResponse =  new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $token = trim($requestData->get('token'));

        if (empty($token)) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        $user = $this->userManager->getUser();
        $username = $user->getUsername();

        $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($username);
        if (empty($emailChangeRequest)) {
            return $redirectResponse;
        }

        if ($token !== $emailChangeRequest['token']) {
            $this->session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        try {
            $this->emailChangeRequestService->confirmEmailChangeRequest($emailChangeRequest);
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_CONFIRM_KEY,
                    self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN
                );
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_EMAIL_VALUE_KEY,
                    $emailChangeRequest['new_email']
                );
            } else {
                $this->session->getFlashBag()->set(
                    self::FLASH_BAG_CONFIRM_KEY,
                    self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN
                );
            }

            return $redirectResponse;
        }

        $oldEmail = $username;
        $newEmail = $emailChangeRequest['new_email'];

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-subscribe',
                [
                    'listId' => 'announcements',
                    'email' => $newEmail,
                ]
            )
        );

        $resqueQueueService->enqueue(
            $resqueJobFactory->create(
                'email-list-unsubscribe',
                [
                    'listId' => 'announcements',
                    'email' => $oldEmail,
                ]
            )
        );

        $user->setUsername($emailChangeRequest['new_email']);
        $this->userManager->setUser($user);

        if (!is_null($request->cookies->get('simplytestable-user'))) {
            $redirectResponse->headers->setCookie($this->userManager->createUserCookie());
        }

        $this->session->getFlashBag()->set(
            self::FLASH_BAG_CONFIRM_KEY,
            self::FLASH_BAG_CONFIRM_MESSAGE_SUCCESS
        );


        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     *
     * @throws InvalidCredentialsException
     */
    public function cancelAction()
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $this->emailChangeRequestService->cancelEmailChangeRequest();
        $this->session->getFlashBag()->set('user_account_details_cancel_email_change_notice', 'cancelled');

        return new RedirectResponse($this->router->generate(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param MailService $mailService
     *
     * @param Twig_Environment $twig
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     */
    private function sendEmailChangeConfirmationToken(MailService $mailService, Twig_Environment $twig)
    {
        $mailServiceConfiguration = $mailService->getConfiguration();
        $user = $this->userManager->getUser();
        $userName = $user->getUsername();

        $emailChangeRequest = $this->emailChangeRequestService->getEmailChangeRequest($userName);

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_email_change_request_confirmation');

        $confirmationUrl = $this->router->generate(
            'view_user_account_index_index',
            [
                'token' => $emailChangeRequest['token'],
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $viewName = 'SimplyTestableWebClientBundle:Email:user-email-change-request-confirmation.txt.twig';

        $message = $mailService->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($emailChangeRequest['new_email']);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($twig->render($viewName, [
            'current_email' => $userName,
            'new_email' => $emailChangeRequest['new_email'],
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $emailChangeRequest['token']
        ]));

        $mailService->getSender()->send($message);
    }
}
