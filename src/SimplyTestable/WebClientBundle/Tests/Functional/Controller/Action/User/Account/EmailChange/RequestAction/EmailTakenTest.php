<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\RequestAction;

class EmailTakenTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_request_notice' => [
                'email-taken'
            ],
            'user_account_details_update_email' => [
                'new-user@example.com'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.1 409'
        ];
    }

}


