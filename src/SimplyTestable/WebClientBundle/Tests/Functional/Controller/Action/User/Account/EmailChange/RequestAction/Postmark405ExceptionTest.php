<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\RequestAction;

class Postmark405ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":405,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_request_notice' => [
                'postmark-not-allowed-to-send'
            ],
            'user_account_details_update_email' => [
                'new-user@example.com'
            ]
        ];
    }

}


 