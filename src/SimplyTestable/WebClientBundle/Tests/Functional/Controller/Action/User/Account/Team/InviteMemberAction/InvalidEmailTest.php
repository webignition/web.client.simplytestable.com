<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class InvalidEmailTest extends ActionTest {

    protected function getRequestPostData() {
        return [
            'email' => '1asd@example'
        ];
    }

    public function testHasSingleMessageInMailServiceHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }


    public function testMailServiceHasError() {
        $this->assertTrue($this->getMailService()->getSender()->getLastResponse()->isError());
    }


    public function testMailServiceError() {
        $this->assertEquals(300, $this->getMailService()->getSender()->getLastResponse()->getErrorCode());
    }


    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'error',
            'error' => 'invalid-email',
            'invitee' => '1asd@example'
        ]);
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
        $this->getMailService()->getSender()->setJsonResponse('{"ErrorCode":300,"Message":"Invalid \'To\' address: \'1asd@example\'."}');
    }


    protected function getHttpFixtureItems() {
        return array(
            $this->getTeamInviteGetHttpResponseFixture(),
            'HTTP/1.0 200 OK',
            'HTTP/1.0 200 OK',
            'HTTP/1.0 200 OK'
        );
    }


    private function getTeamInviteGetHttpResponseFixture() {
        return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{"team":"Team Name","user":"1asd@example"}
EOD;
    }
}