<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ConfirmAction;

class BlankTokenTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestToken() {
        return '';
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_confirm_notice' => [
                'invalid-token'
            ]
        ];
    }

}


