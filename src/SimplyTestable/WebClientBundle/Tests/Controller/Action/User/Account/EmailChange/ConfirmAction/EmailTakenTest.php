<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ConfirmAction;

class EmailTakenTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_confirm_notice' => [
                'email-taken'
            ],
            'user_account_details_update_email' => [
                'new-user@example.com'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getEmailChangeRequestHttpFixture(),
            'HTTP/1.1 409'
        ];
    }


    private function getEmailChangeRequestHttpFixture() {
        return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

{"new_email":"new-user@example.com","token":"foo"}
EOD;

    }

}


