<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

class BlankEmailTest extends ActionTest {

    protected function getRequestEmailAddress() {
        return '';
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/reset-password/';
    }

    protected function getExpectedFlashValues() {
        return [
            'user_reset_password_error' => [
                0 => 'blank-email'
            ]
        ];
    }

}


 