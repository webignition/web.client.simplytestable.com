<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\ResendInviteAction;

class UserNotEnabledTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_resend', [
            'status' => 'success',
            'invitee' => 'invitee@example.com',
            'team' => 'Foo'
        ]);
    }

    public function testMailServiceHasHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testNotificationMessageContainsNewUserContent() {
        $this->assertNotificationMessageContains('You have been invited to join the Foo testing team at SimplyTestable.com');
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getInviteRequestHttpFixture(),
            'HTTP/1.1 404',
            'HTTP/1.1 404',
            'HTTP/1.1 404'
        ];
    }

    private function getInviteRequestHttpFixture() {
        return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

{"team":"Foo","user":"invitee@example.com","token":"ada90a0a98f6441267ae0777d4ef9f50"}
EOD;

    }


    protected function getRequestPostData() {
        return [
            'email' => 'invitee@example.com'
        ];
    }
}