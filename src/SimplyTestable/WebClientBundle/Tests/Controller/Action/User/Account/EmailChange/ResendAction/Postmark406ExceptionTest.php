<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ResendAction;

class Postmark406ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":406,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_resend_email_change_error' => [
                0 => 'postmark-inactive-recipient'
            ]
        ];
    }

}


 