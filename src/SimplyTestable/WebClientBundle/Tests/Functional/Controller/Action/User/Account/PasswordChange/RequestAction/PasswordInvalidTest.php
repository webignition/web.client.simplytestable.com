<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction;

class PasswordInvalidTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestCurrentPassword() {
        return 'foo1';
    }

    protected function getRequestNewPassword() {
        return 'foo2';
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_password_request_notice' => [
                'password-invalid'
            ]
        ];
    }

}


