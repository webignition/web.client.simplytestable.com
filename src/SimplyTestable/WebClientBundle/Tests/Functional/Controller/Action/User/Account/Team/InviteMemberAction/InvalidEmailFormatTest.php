<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\InviteMemberAction;

class InvalidEmailFormatTest extends ActionTest {

    protected function getRequestPostData() {
        return [
            'email' => 'foo'
        ];
    }

    public function testHasNoMessagesInMailServiceHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNull($postmarkSender->getLastMessage());
        $this->assertNull($postmarkSender->getLastResponse());
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