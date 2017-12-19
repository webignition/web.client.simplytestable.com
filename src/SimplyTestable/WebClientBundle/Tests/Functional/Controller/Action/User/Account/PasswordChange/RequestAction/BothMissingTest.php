<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction;

class BothMissingTest extends PasswordMissingTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestCurrentPassword() {
        return '';
    }

    protected function getRequestNewPassword() {
        return '';
    }

}


