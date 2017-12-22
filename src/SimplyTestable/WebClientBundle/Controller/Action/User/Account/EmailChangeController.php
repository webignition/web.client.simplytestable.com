<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailChangeController extends AccountCredentialsChangeController
{
    const FLASH_BAG_CONFIRM_KEY = 'user_account_details_update_email_confirm_notice';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_TOKEN_INVALID = 'invalid-token';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_EMAIL_TAKEN = 'email-taken';
    const FLASH_BAG_CONFIRM_ERROR_MESSAGE_UNKNOWN = 'unknown';
    const FLASH_BAG_CONFIRM_MESSAGE_SUCCESS = 'success';

    const FLASH_BAG_EMAIL_VALUE_KEY = 'user_account_details_update_email';

    /**
     * @return RedirectResponse
     */
    public function getUserSignInRedirectResponse()
    {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], UrlGeneratorInterface::ABSOLUTE_URL));
    }

    public function requestAction() {
        $redirectResponse = $this->redirect($this->generateUrl('view_user_account_index_index', [], true));

        if ($this->getRequestEmailAddress() === '') {
            $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'blank-email');
            return $redirectResponse;
        }

        if ($this->getRequestEmailAddress() == $this->getUser()->getUsername()) {
            $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'same-email');
            return $redirectResponse;
        }

        if (!$this->isEmailValid($this->getRequestEmailAddress())) {
            $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'invalid-email');
            $this->get('session')->getFlashBag()->set('user_account_details_update_email', $this->getRequestEmailAddress());
            return $redirectResponse;
        }

        $this->getUserService()->setUser($this->getUser());
        $response = $this->getUserEmailChangeRequestService()->createEmailChangeRequest($this->getRequestEmailAddress());

        if ($response === true) {
            try {
                $this->sendEmailChangeConfirmationToken();
                $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'email-done');
            } catch (PostmarkResponseException $postmarkResponseException) {
                $this->getUserEmailChangeRequestService()->cancelEmailChangeRequest();

                if ($postmarkResponseException->isNotAllowedToSendException()) {
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'postmark-not-allowed-to-send');
                } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'postmark-inactive-recipient');
                } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'invalid-email');
                } else {
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'postmark-failure');
                }

                $this->get('session')->getFlashBag()->set('user_account_details_update_email', $this->getRequestEmailAddress());
            }
        } else {
            switch ($response) {
                case 409:
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'email-taken');
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email', $this->getRequestEmailAddress());
                    break;

                default:
                    $this->get('session')->getFlashBag()->set('user_account_details_update_email_request_notice', 'unknown');
            }
        }

        return $redirectResponse;
    }


    public function resendAction() {
        try {
            $this->sendEmailChangeConfirmationToken();
            $this->get('session')->getFlashBag()->set('user_account_details_resend_email_change_notice', 're-sent');
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->get('session')->getFlashBag()->set('user_account_details_resend_email_change_error', 'postmark-not-allowed-to-send');
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->get('session')->getFlashBag()->set('user_account_details_resend_email_change_error', 'postmark-inactive-recipient');
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $this->get('session')->getFlashBag()->set('user_account_details_resend_email_change_error', 'invalid-email');
            } else {
                $this->get('session')->getFlashBag()->set('user_account_details_resend_email_change_error', 'postmark-failure');
            }
        }

        return $this->redirect($this->generateUrl(
            'view_user_account_index_index',
            [],
            true
        ));
    }


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
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    private function sendEmailChangeConfirmationToken() {
        $emailChangeRequest = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());

        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_email_change_request_confirmation');

        $confirmationUrl = $this->generateUrl('view_user_account_index_index', array(), true).'?token=' . $emailChangeRequest['token'];

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($emailChangeRequest['new_email']);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-email-change-request-confirmation.txt.twig', array(
            'current_email' => $this->getUser()->getUsername(),
            'new_email' => $emailChangeRequest['new_email'],
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $emailChangeRequest['token']
        )));

        $this->getMailService()->getSender()->send($message);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserEmailChangeRequestService
     */
    private function getUserEmailChangeRequestService() {
        return $this->get('simplytestable.services.useremailchangerequestservice');
    }


    /**
     *
     * @return string
     */
    private function getRequestEmailAddress() {
        return strtolower(trim($this->get('request')->request->get('email')));
    }

    /**
     *
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {
        $validator = new EmailValidator;
        return $validator->isValid($email);
    }
}