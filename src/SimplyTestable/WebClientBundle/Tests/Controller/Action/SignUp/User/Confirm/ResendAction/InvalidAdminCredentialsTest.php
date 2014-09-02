<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

class InvalidAdminCredentialsTest extends ActionTest {

    protected function getExpectedFlashValues() {
        return [
            'token_resend_error' => [
                0 => 'core-app-invalid-credentials'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return array(
            'HTTP/1.0 401'
        );
    }
    

}


 