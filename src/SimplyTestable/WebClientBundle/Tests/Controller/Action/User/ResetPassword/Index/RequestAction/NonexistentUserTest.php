<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

class NonexistentUserTest extends ActionTest {

    protected function getExpectedFlashValues() {
        return [
            'user_reset_password_error' => [
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


 