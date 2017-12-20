<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction;

class NewPasswordMissingTest extends PasswordMissingTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestCurrentPassword() {
        return 'foo';
    }

    protected function getRequestNewPassword() {
        return '';
    }

}


