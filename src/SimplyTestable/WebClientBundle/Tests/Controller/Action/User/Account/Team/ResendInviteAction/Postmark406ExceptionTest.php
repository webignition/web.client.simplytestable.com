<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\Team\ResendInviteAction;

class Postmark406ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":406,"Message":""}';
    }

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_resend', [
            'status' => 'error',
            'invitee' => 'invitee@example.com',
            'error' => 'postmark-inactive-recipient'
        ]);
    }

}


 