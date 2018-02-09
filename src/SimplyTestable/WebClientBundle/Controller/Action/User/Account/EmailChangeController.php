<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use SimplyTestable\WebClientBundle\Exception\UserEmailChangeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception as MailConfigurationException;

class EmailChangeController extends AccountCredentialsChangeController
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
     * {@inheritdoc}
     */
    public function getUserSignInRedirectResponse(Request $request)
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     * @throws MailConfigurationException
     */
    public function requestAction(Request $request)
    {
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $session = $this->container->get('session');
        $userService = $this->container->get('simplytestable.services.userservice');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $user = $userService->getUser();
        $username = $user->getUsername();

        $newEmail = strtolower(trim($requestData->get('email')));

        if (empty($newEmail)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_EMPTY
            );

            return $redirectResponse;
        }

        if ($newEmail === $username) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_EMAIL_SAME
            );

            return $redirectResponse;
        }

        $emailValidator = new EmailValidator;
        $emailValidator->isValid($newEmail);

        if (!$emailValidator->isValid($newEmail)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID
            );

            $session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );

            return $redirectResponse;
        }

        $userService->setUser($user);

        try {
            $emailChangeRequestService->createEmailChangeRequest($newEmail);
            $this->sendEmailChangeConfirmationToken();

            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
            );
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_TAKEN;
            } else {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_UNKNOWN;
            }

            $session->getFlashBag()->set(
                self::FLASH_BAG_REQUEST_KEY,
                $flashMessage
            );

            $session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        } catch (PostmarkResponseException $postmarkResponseException) {
            $emailChangeRequestService->cancelEmailChangeRequest();

            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND;
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT;
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $flashMessage = self::FLASH_BAG_REQUEST_ERROR_MESSAGE_EMAIL_INVALID;
            } else {
                $flashMessage = self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN;
            }

            $session->getFlashBag()->set(self::FLASH_BAG_REQUEST_KEY, $flashMessage);

            $session->getFlashBag()->set(
                self::FLASH_BAG_EMAIL_VALUE_KEY,
                $newEmail
            );
        }

        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     */
    public function resendAction()
    {
        $session = $this->container->get('session');

        try {
            $this->sendEmailChangeConfirmationToken();
            $session->getFlashBag()->set(
                self::FLASH_BAG_RESEND_SUCCESS_KEY,
                self::FLASH_BAG_RESEND_MESSAGE_SUCCESS
            );
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_NOT_ALLOWED_TO_SEND
                );
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_INACTIVE_RECIPIENT
                );
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_RESEND_ERROR_MESSAGE_EMAIL_INVALID
                );
            } else {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_RESEND_ERROR_KEY,
                    self::FLASH_BAG_ERROR_MESSAGE_POSTMARK_UNKNOWN
                );
            }
        }

        return $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws \CredisException
     * @throws \Exception
     * @throws InvalidAdminCredentialsException
     * @throws InvalidCredentialsException
     */
    public function confirmAction(Request $request)
    {
        $session = $this->container->get('session');
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $resqueQueueService = $this->container->get('simplytestable.services.resque.queueservice');
        $resqueJobFactory = $this->container->get('simplytestable.services.resque.jobfactoryservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

        $requestData = $request->request;

        $redirectResponse =  $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $token = trim($requestData->get('token'));

        if (empty($token)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        $user = $userService->getUser();
        $username = $user->getUsername();

        $emailChangeRequest = $emailChangeRequestService->getEmailChangeRequest($username);
        if ($token !== $emailChangeRequest['token']) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        try {
            $emailChangeRequestService->confirmEmailChangeRequest($emailChangeRequest);
        } catch (UserEmailChangeException $userEmailChangeException) {
            if ($userEmailChangeException->isEmailAddressAlreadyTakenException()) {
                $session->getFlashBag()->set(
                    self::FLASH_BAG_CONFIRM_KEY,
                    self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN
                );
                $session->getFlashBag()->set(
                    self::FLASH_BAG_EMAIL_VALUE_KEY,
                    $emailChangeRequest['new_email']
                );
            } else {
                $session->getFlashBag()->set(
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
        $userService->setUser($user);

        if (!is_null($request->cookies->get('simplytestable-user'))) {
            $serializedUser = $userSerializerService->serializeToString($user);
            $userCookie = $this->createUserAuthenticationCookie($serializedUser);

            $redirectResponse->headers->setCookie($userCookie);
        }

        $this->get('session')->getFlashBag()->set(
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
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $session = $this->container->get('session');

        $emailChangeRequestService->cancelEmailChangeRequest();
        $session->getFlashBag()->set('user_account_details_cancel_email_change_notice', 'cancelled');

        return $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }

    /**
     * @throws InvalidAdminCredentialsException
     * @throws MailConfigurationException
     * @throws PostmarkResponseException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    private function sendEmailChangeConfirmationToken()
    {
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $userService = $this->container->get('simplytestable.services.userservice');

        $mailServiceConfiguration = $mailService->getConfiguration();
        $user = $userService->getUser();
        $userName = $user->getUsername();

        $emailChangeRequest = $emailChangeRequestService->getEmailChangeRequest($userName);

        $sender = $mailServiceConfiguration->getSender('default');
        $messageProperties = $mailServiceConfiguration->getMessageProperties('user_email_change_request_confirmation');

        $confirmationUrl = $this->generateUrl(
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
        $message->setTextMessage($this->renderView($viewName, [
            'current_email' => $userName,
            'new_email' => $emailChangeRequest['new_email'],
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $emailChangeRequest['token']
        ]));

        $mailService->getSender()->send($message);
    }
}
