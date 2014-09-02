<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

class Postmark406ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":406,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'user_reset_password_error' => [
                0 => 'postmark-inactive-recipient'
            ]
        ];
    }

}


 