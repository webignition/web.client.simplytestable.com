<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class InviteLeaderTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'error',
            'error' => 'invitee-is-a-team-leader',
            'invitee' => 'invitee@example.com'
        ]);
    }

    public function testHasNoMessagesInMailServiceHistory() {
        $this->assertEquals(0, $this->getMailService()->getSender()->getHistory()->count());
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
HTTP/1.1 400 Bad Request
X-TeamInviteGet-Error-Code: 2
X-TeamInviteGet-Error-Message: Invitee is a team leader
EOD;
    }


    protected function getRequestPostData() {
        return [
            'email' => 'invitee@example.com'
        ];
    }
}