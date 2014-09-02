<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

class Postmark406ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":406,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'token_resend_error' => [
                0 => 'postmark-inactive-recipient'
            ]
        ];
    }

}


 