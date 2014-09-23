<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;

class EmailChangeController extends AccountCredentialsChangeController {

    /**
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getUserSignInRedirectResponse() {
        return new RedirectResponse($this->generateUrl('view_user_signin_index', [
            'redirect' => base64_encode(json_encode(['route' => 'view_user_account_index_index']))
        ], true));
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


    public function confirmAction() {
        $redirectResponse =  $this->redirect($this->generateUrl('view_user_account_index_index', [], true));

        if ($this->getRequestToken() === '') {
            $this->get('session')->getFlashBag()->set('user_account_details_update_email_confirm_notice', 'invalid-token');
            return $redirectResponse;
        }

        $emailChangeRequest = $this->getUserEmailChangeRequestService()->getEmailChangeRequest($this->getUser()->getUsername());
        if ($this->getRequestToken() !== $emailChangeRequest['token']) {
            $this->get('session')->getFlashBag()->set('user_account_details_update_email_confirm_notice', 'invalid-token');
            return $redirectResponse;
        }

        $result = $this->getUserEmailChangeRequestService()->confirmEmailChangeRequest($this->getRequestToken());

        if ($result !== true) {
            if ($result == 409) {
                $this->get('session')->getFlashBag()->set('user_account_details_update_email_confirm_notice', 'email-taken');
                $this->get('session')->getFlashBag()->set('user_account_details_update_email', $emailChangeRequest['new_email']);
            } else {
                $this->get('session')->getFlashBag()->set('user_account_details_update_email_confirm_notice', 'unknown');
            }

            return $redirectResponse;
        }

        $oldEmail = $this->getUser()->getUsername();
        $newEmail = $emailChangeRequest['new_email'];

        $this->getResqueQueueService()->enqueue(
            $this->getResqueJobFactoryService()->create(
                'email-list-subscribe',
                array(
                    'listId' => 'announcements',
                    'email' => $newEmail,
                )
            )
        );

        $this->getResqueQueueService()->enqueue(
            $this->getResqueJobFactoryService()->create(
                'email-list-unsubscribe',
                array(
                    'listId' => 'announcements',
                    'email' => $oldEmail,
                )
            )
        );

        $user = $this->getUser();
        $user->setUsername($emailChangeRequest['new_email']);
        $this->getUserService()->setUser($user, true);

        if (!is_null($this->getRequest()->cookies->get('simplytestable-user'))) {
            $redirectResponse->headers->setCookie($this->getUserAuthenticationCookie());
        }

        $this->get('session')->getFlashBag()->set('user_account_details_update_email_confirm_notice', 'success');


        return $redirectResponse;
    }


    public function cancelAction() {
        $this->getUserEmailChangeRequestService()->cancelEmailChangeRequest();
        $this->get('session')->getFlashBag()->set('user_account_details_cancel_email_change_notice', 'cancelled');
        return $this->redirect($this->generateUrl('view_user_account_index_index', array(), true));
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
     * @return string
     */
    private function getRequestToken() {
        return strtolower(trim($this->get('request')->request->get('token')));
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


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Resque\QueueService
     */
    private function getResqueQueueService() {
        return $this->container->get('simplytestable.services.resque.queueService');
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Resque\JobFactoryService
     */
    private function getResqueJobFactoryService() {
        return $this->container->get('simplytestable.services.resque.jobFactoryService');
    }


}