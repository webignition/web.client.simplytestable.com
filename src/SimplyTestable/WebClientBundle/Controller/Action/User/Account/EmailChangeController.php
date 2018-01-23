<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @return RedirectResponse
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    public function requestAction()
    {
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $session = $this->container->get('session');
        $request = $this->container->get('request');
        $userService = $this->container->get('simplytestable.services.userservice');

        $requestData = $request->request;

        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $user = $this->getUser();
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
        $response = $emailChangeRequestService->createEmailChangeRequest($newEmail);

        if ($response === true) {
            try {
                $this->sendEmailChangeConfirmationToken();
                $session->getFlashBag()->set(
                    self::FLASH_BAG_REQUEST_KEY,
                    self::FLASH_BAG_REQUEST_MESSAGE_SUCCESS
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
        } else {
            if ($response == 409) {
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
        }

        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
     *
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
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
     * @return RedirectResponse
     * @throws \CredisException
     * @throws \Exception
     */
    public function confirmAction()
    {
        $request = $this->container->get('request');
        $session = $this->container->get('session');
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $resqueQueueService = $this->container->get('simplytestable.services.resque.queueservice');
        $resqueJobFactory = $this->container->get('simplytestable.services.resque.jobfactoryservice');

        $requestData = $request->request;

        $redirectResponse =  $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));

        $token = strtolower(trim($requestData->get('token')));

        if (empty($token)) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        $user = $this->getUser();
        $username = $user->getUsername();

        $emailChangeRequest = $emailChangeRequestService->getEmailChangeRequest($username);
        if ($token !== $emailChangeRequest['token']) {
            $session->getFlashBag()->set(
                self::FLASH_BAG_CONFIRM_KEY,
                self::FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID
            );

            return $redirectResponse;
        }

        $result = $emailChangeRequestService->confirmEmailChangeRequest($token);

        if ($result !== true) {
            if ($result == 409) {
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
        $this->getUserService()->setUser($user);

        if (!is_null($this->getRequest()->cookies->get('simplytestable-user'))) {
            $redirectResponse->headers->setCookie($this->getUserAuthenticationCookie());
        }

        $this->get('session')->getFlashBag()->set(
            self::FLASH_BAG_CONFIRM_KEY,
            self::FLASH_BAG_CONFIRM_MESSAGE_SUCCESS
        );


        return $redirectResponse;
    }

    /**
     * @return RedirectResponse
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
     * @throws PostmarkResponseException
     * @throws \SimplyTestable\WebClientBundle\Exception\Mail\Configuration\Exception
     */
    private function sendEmailChangeConfirmationToken()
    {
        $emailChangeRequestService = $this->get('simplytestable.services.useremailchangerequestservice');
        $mailService = $this->container->get('simplytestable.services.mail.service');
        $mailServiceConfiguration = $mailService->getConfiguration();

        $emailChangeRequest = $emailChangeRequestService->getEmailChangeRequest($this->getUser()->getUsername());

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
            'current_email' => $this->getUser()->getUsername(),
            'new_email' => $emailChangeRequest['new_email'],
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $emailChangeRequest['token']
        ]));

        $mailService->getSender()->send($message);
    }
}
