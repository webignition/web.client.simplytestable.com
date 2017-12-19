<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\SignUp\User\Confirm\ResendAction;

class NonexistentUserTest extends ActionTest {

    protected function getExpectedFlashValues() {
        return [
            'token_resend_error' => [
                0 => 'invalid-user'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return array(
            'HTTP/1.0 404'
        );
    }
    

}


 