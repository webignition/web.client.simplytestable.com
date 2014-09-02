<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

class Postmark405ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":405,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'token_resend_error' => [
                0 => 'postmark-not-allowed-to-send'
            ]
        ];
    }

}


 