<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\Team\InviteMemberAction;

class ExistingUserTest extends ActionTest {

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
            $this->getTeamInviteGetHttpResponseFixture()
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