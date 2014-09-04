<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\CancelAction;

class IsCancelledTest extends ActionTest {

    const TOKEN = 'foo';

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_cancel_email_change_notice' => [
                0 => 'cancelled'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.1 200'
        ];
    }

}


