<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\RequestAction;

class Postmark406ExceptionTest extends PostmarkExceptionTest {

    protected function getPostmarkJsonResponse() {
        return '{"ErrorCode":406,"Message":""}';
    }


    protected function getExpectedFlashValues() {
        return [
            'user_account_details_update_email_request_notice' => [
                'postmark-inactive-recipient'
            ],
            'user_account_details_update_email' => [
                'new-user@example.com'
            ]
        ];
    }

}


 