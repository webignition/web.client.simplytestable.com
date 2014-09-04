<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\PasswordChange\RequestAction;

class CurrentPasswordMissingTest extends PasswordMissingTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestCurrentPassword() {
        return '';
    }

    protected function getRequestNewPassword() {
        return 'foo';
    }

}


