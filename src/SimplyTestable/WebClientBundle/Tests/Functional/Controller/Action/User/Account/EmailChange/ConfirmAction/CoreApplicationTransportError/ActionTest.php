<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ConfirmAction\CoreApplicationTransportError;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ConfirmAction\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getCoreApplicationResponseFixture();

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_confirm_notice' => [
                'unknown'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getEmailChangeRequestHttpFixture(),
            $this->getCoreApplicationResponseFixture()
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


