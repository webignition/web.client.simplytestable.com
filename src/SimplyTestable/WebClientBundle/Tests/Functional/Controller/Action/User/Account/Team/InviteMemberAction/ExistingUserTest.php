<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class ExistingUserTest extends ActionTest {

    protected function getRequestPostData() {
        return [
            'email' => 'invitee@example.com'
        ];
    }

    public function testHasSingleMessageInMailServiceHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testMailSubject() {
        $this->assertLastMailMessageSubjectContains('You have been invited to join the Team Name team');
    }

    public function testMailContent() {
        $this->assertLastMailMessageTextContains('You have been invited to join the Team Name team');
    }

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'success',
            'invitee' => 'invitee@example.com',
            'team' => 'Team Name'
        ]);
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }

    protected function getHttpFixtureItems() {
        return array(
            $this->getTeamInviteGetHttpResponseFixture(),
            'HTTP/1.0 200 OK',
            'HTTP/1.0 200 OK'
        );
    }


    private function getTeamInviteGetHttpResponseFixture() {
        return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{"team":"Team Name","user":"invitee@example.com"}
EOD;
    }
}