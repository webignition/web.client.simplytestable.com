<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\BaseController;
use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class IndexController extends BaseController {

    public function requestAction() {
        $email = trim($this->get('request')->request->get('email'));

        if ($email == '') {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'blank-email');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(), true));
        }

        if (!$this->isEmailValid($email)) {
            $this->get('session')->getFlashBag()->set('user_reset_password_error', 'invalid-email');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(
                'email' => $email
            ), true));
        }

        try {
            if ($this->getUserService()->exists($email) === false) {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'invalid-user');
                return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array('email' => $email), true));
            }
        } catch (CoreApplicationAdminRequestException $coreApplicationAdminRequestException) {
            if ($coreApplicationAdminRequestException->isInvalidCredentialsException()) {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'core-app-invalid-credentials');
                $this->sendInvalidAdminCredentialsNotification();
            } else {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'core-app-unknown-error');
            }

            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array('email' => $email), true));
        }

        $token = $this->getUserService()->getConfirmationToken($email);

        try {
            $this->sendPasswordResetConfirmationToken($email, $token);
            $this->get('session')->getFlashBag()->set('user_reset_password_confirmation', 'token-sent');
            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array('email' => $email), true));
        } catch (PostmarkResponseException $postmarkResponseException) {
            if ($postmarkResponseException->isNotAllowedToSendException()) {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'postmark-not-allowed-to-send');
            } elseif ($postmarkResponseException->isInactiveRecipientException()) {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'postmark-inactive-recipient');
            } elseif ($postmarkResponseException->isInvalidEmailAddressException()) {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'invalid-email');
            } else {
                $this->get('session')->getFlashBag()->set('user_reset_password_error', 'postmark-failure');
            }

            return $this->redirect($this->generateUrl('view_user_resetpassword_index_index', array(
                'email' => $email
            ), true));
        }
    }


    /**
     *
     * @param string $email
     * @param  $token
     * @throws PostmarkResponseException
     */
    private function sendPasswordResetConfirmationToken($email, $token) {
        $sender = $this->getMailService()->getConfiguration()->getSender('default');
        $messageProperties = $this->getMailService()->getConfiguration()->getMessageProperties('user_reset_password');

        $confirmationUrl = $this->generateUrl('view_user_resetpassword_choose_index', array(
            'email' => $email,
            'token' => $token
        ), true);

        $message = $this->getMailService()->getNewMessage();
        $message->setFrom($sender['email'], $sender['name']);
        $message->addTo($email);
        $message->setSubject($messageProperties['subject']);
        $message->setTextMessage($this->renderView('SimplyTestableWebClientBundle:Email:reset-password-confirmation.txt.twig', array(
            'confirmation_url' => $confirmationUrl,
            'email' => $email
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
     * @param string $email
     * @return boolean
     */
    private function isEmailValid($email) {
        $validator = new EmailValidator;
        return $validator->isValid($email);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    private function getMailService() {
        return $this->get('simplytestable.services.mail.service');
    }
}