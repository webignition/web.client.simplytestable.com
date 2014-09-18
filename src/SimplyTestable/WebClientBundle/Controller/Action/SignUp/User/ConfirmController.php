<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\SignUp\User;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class ConfirmController extends BaseController {

    public function resendAction($email) {
        $redirectResponse = $this->redirect($this->generateUrl(
            'view_user_signup_confirm_index',
            ['email' => $email]
        ));

        try {
            if ($this->getUserService()->exists($email) === false) {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'invalid-user');
                return $redirectResponse;
            }
        } catch (CoreApplicationAdminRequestException $coreApplicationAdminRequestException) {
            if ($coreApplicationAdminRequestException->isInvalidCredentialsException()) {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'core-app-invalid-credentials');
                $this->sendInvalidAdminCredentialsNotification();
            } else {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'core-app-unknown-error');
            }

            return $redirectResponse;
        }

        $token = $this->getUserService()->getConfirmationToken($email);

        try {
            $this->sendConfirmationToken($email, $token);
            $this->get('session')->getFlashBag()->set('token_resend_confirmation', 'sent');

            return $redirectResponse;
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'postmark-not-allowed-to-send');
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'postmark-inactive-recipient');
            } else {
                $this->get('session')->getFlashBag()->set('token_resend_error', 'postmark-failure');
            }

            return $redirectResponse;
        }
    }


    /**
     *
     * @param string $email
     * @param  $token
     * @throws PostmarkResponseException
     */
    private function sendConfirmationToken($email, $token) {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_creation_confirmation');

        $confirmationUrl = $this->generateUrl('view_user_signup_confirm_index', array(
                'email' => $email
            ), true).'?token=' . $token;

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:user-creation-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'confirmation_code' => $token
        )));

        $this->getMailService()->getSender()->send($message);
    }


    private function sendInvalidAdminCredentialsNotification() {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo('jon@simplytestable.com');
        $message->setSubject('Invalid admin user credentials');
        $message->setTextMessage('Invalid admin user credentials exception raised when calling UserService::exists()');

        $this->getMailService()->getSender()->send($message);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }
}