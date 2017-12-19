<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction\CoreApplicationTransportError;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getCoreApplicationResponseFixture();

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_password_request_notice' => [
                'unknown'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getPasswordChangeTokenHttpFixture(),
            $this->getCoreApplicationResponseFixture()
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


