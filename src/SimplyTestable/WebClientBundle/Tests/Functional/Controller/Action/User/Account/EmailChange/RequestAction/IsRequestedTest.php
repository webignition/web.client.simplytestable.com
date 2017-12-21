<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\RequestAction;

class IsRequestedTest extends ActionTest {

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    public function testMailServiceHasHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
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
            'user_account_details_update_email_request_notice' => [
                'email-done'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.1 200',
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


