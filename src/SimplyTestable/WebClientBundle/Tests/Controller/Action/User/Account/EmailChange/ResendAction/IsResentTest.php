<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ResendAction;

class IsResentTest extends ActionTest {

    const TOKEN = 'foo';

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }


    public function testMailServiceHasHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testNotificationMessageEmailChangeDetails() {
        $this->assertNotificationMessageContains('You requested to change your Simply Testable account email address from');
        $this->assertNotificationMessageContains('user@example.com to new-user@example.com.');
    }

    public function testNotificationMessageContainsTokenLine() {
        $this->assertNotificationMessageContains('Your confirmation code is foo');
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_resend_email_change_notice' => [
                0 => 're-sent'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getEmailChangeRequestHttpFixture()
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


