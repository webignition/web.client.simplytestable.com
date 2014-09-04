<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\RequestAction;

class InvalidEmailTest extends ActionTest {

    const EMAIL = 'foo';

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getRequestEmailAddress() {
        return self::EMAIL;
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_request_notice' => [
                'invalid-email'
            ],
            'user_account_details_update_email' => [
                self::EMAIL
            ]
        ];
    }

}


