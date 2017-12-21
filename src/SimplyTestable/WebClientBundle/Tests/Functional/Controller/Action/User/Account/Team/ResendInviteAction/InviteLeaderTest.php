<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\ResendInviteAction;

class InviteLeaderTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_resend', [
            'status' => 'error',
            'error' => 'invitee-is-a-team-leader',
            'invitee' => 'invitee@example.com'
        ]);
    }

    public function testHasNoMessagesInMailServiceHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNull($postmarkSender->getLastMessage());
        $this->assertNull($postmarkSender->getLastResponse());
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
            'user' => 'invitee@example.com'
        ];
    }
}