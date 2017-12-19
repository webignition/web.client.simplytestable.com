<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ResendAction;

class Postmark405ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":405,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_resend_email_change_error' => [
                0 => 'postmark-not-allowed-to-send'
            ]
        ];
    }

}


 