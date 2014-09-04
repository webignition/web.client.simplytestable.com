<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\User\Account;

//use Egulias\EmailValidator\EmailValidator;
//use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class PasswordChangeController extends AccountCredentialsChangeController {

    public function requestAction() {
        $redirectResponse = $this->redirect($this->generateUrl('view_user_account_index_index', [], true));

        if (!$this->hasRequestCurrentPassword() || !$this->hasRequestNewPassword()) {
            $this->get('session')->setFlash('user_account_details_update_password_request_notice', 'password-missing');
            return $redirectResponse;
        }

        if ($this->getRequestCurrentPassword() != $this->getUser()->getPassword()) {
            $this->get('session')->setFlash('user_account_details_update_password_request_notice', 'password-invalid');
            return $redirectResponse;
        }

        $this->getUserService()->setUser($this->getUser());
        $passwordResetResponse = $this->getUserService()->resetLoggedInUserPassword($this->getRequestNewPassword());

        if ($passwordResetResponse === true) {
            $user = $this->getUser();
            $user->setPassword($this->getRequestNewPassword());
            $this->getUserService()->setUser($user, true);

            if (!is_null($this->getRequest()->cookies->get('simplytestable-user'))) {
                $redirectResponse->headers->setCookie($this->getUserAuthenticationCookie());
            }

            $this->get('session')->setFlash('user_account_details_update_password_request_notice', 'password-done');

        } else {
            switch ($passwordResetResponse) {
                case 503:
                    $this->get('session')->setFlash('user_account_details_update_password_request_notice', 'password-failed-read-only');
                    break;

                default:
                    $this->get('session')->setFlash('user_account_details_update_password_request_notice', 'unknown');
            }
        }



        return $redirectResponse;
    }


    /**
     * @return bool
     */
    private function hasRequestCurrentPassword() {
        return $this->getRequestCurrentPassword() !== '';
    }


    /**
     * @return bool
     */
    private function hasRequestNewPassword() {
        return $this->getRequestNewPassword() !== '';
    }


    /**
     *
     * @return string
     */
    private function getRequestCurrentPassword() {
        return strtolower(trim($this->get('request')->request->get('current-password')));
    }


    /**
     *
     * @return string
     */
    private function getRequestNewPassword() {
        return strtolower(trim($this->get('request')->request->get('new-password')));
    }

}