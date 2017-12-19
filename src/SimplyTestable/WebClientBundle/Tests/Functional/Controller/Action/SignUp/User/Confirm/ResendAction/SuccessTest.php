<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\SignUp\User\Confirm\ResendAction;

class SuccessTest extends ActionTest {

    protected function getExpectedFlashValues() {
        return [
            'token_resend_confirmation' => [
                0 => 'sent'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return array(
            'HTTP/1.0 200',
            'HTTP/1.0 200'
        );
    }
    

}


 