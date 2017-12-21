<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class InviteSelfTest extends ActionTest {

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_get', [
            'status' => 'error',
            'error' => 'invite-self'
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


    protected function getRequestPostData() {
        return [
            'email' => 'user@example.com'
        ];
    }
}