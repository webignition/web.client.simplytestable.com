<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\RequestAction;

class SameEmailTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestEmailAddress() {
        return $this->makeUser()->getUsername();
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_request_notice' => [
                0 => 'same-email'
            ]
        ];
    }

}


