<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\Team\ResendInviteAction;

class UserIsEnabledTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_resend', [
            'status' => 'success',
            'invitee' => 'invitee@example.com',
            'team' => 'Foo'
        ]);
    }

    public function testMailServiceHasHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testNotificationMessageContainsEnabledUserContent() {
        $this->assertNotificationMessageContains('You can accept or decline the invite from your account team page');
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getInviteRequestHttpFixture(),
            'HTTP/1.1 200',
            'HTTP/1.1 200',
            'HTTP/1.1 200'
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
            'user' => 'invitee@example.com'
        ];
    }
}