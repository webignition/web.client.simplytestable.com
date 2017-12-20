<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\ResendInviteAction;

class Postmark405ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":405,"Message":""}';
    }

    public function testFlashValue() {
        $this->assertFlashValueIs('team_invite_resend', [
            'status' => 'error',
            'invitee' => 'invitee@example.com',
            'error' => 'postmark-not-allowed-to-send'
        ]);
    }

}


 