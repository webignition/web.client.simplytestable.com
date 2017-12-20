<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction;

class IsRequestedTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_password_request_notice' => [
                'password-done'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getPasswordChangeTokenHttpFixture(),
            'HTTP/1.1 200'
        ];
    }


    private function getPasswordChangeTokenHttpFixture() {
        return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

"4ul1iu9hl12cc8040o08k4owckc0woo004g44ow8wc0k84kkck"
EOD;

    }

}


