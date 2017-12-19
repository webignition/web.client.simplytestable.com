<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class InvalidEmailFormatTest extends ActionTest {

    protected function getRequestPostData() {
        return [
            'email' => 'foo'
        ];
    }

    public function testHasNoMessagesInMailServiceHistory() {
        $this->assertEquals(0, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'error',
            'error' => 'invalid-invitee',
            'invitee' => 'foo'
        ]);
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }
}