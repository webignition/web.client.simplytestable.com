<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\PasswordChange\RequestAction;

abstract class PasswordMissingTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_password_request_notice' => [
                'password-missing'
            ]
        ];
    }

}


