<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\ResetPassword\Index\RequestAction;

class InvalidEmailTest extends ActionTest {

    const EMAIL = 'foo';

    protected function getRequestEmailAddress() {
        return self::EMAIL;
    }

    protected function getExpectedFlashValues() {
        return [
            'user_reset_password_error' => [
                0 => 'invalid-email'
            ]
        ];
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/reset-password/?email=' . self::EMAIL;
    }

}


 