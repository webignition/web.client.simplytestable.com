<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\Team\InviteMemberAction;

class InviteSelfTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'error',
            'error' => 'invite-self'
        ]);
    }

    public function testHasNoMessagesInMailServiceHistory() {
        $this->assertEquals(0, $this->getMailService()->getSender()->getHistory()->count());
    }

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
    }


    protected function getRequestPostData() {
        return [
            'email' => 'user@example.com'
        ];
    }
}