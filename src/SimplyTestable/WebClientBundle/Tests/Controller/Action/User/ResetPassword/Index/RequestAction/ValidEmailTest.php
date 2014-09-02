<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

class ValidEmailTest extends ActionTest {

    protected function getExpectedFlashValues() {
        return [
            'user_reset_password_confirmation' => [
                0 => 'token-sent'
            ]
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.0 200',
            $this->getConfirmationTokenHttpFixture()
        ];
    }


    private function getConfirmationTokenHttpFixture() {
return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

"1vhsw82pow9ww4sksogw4owc408kcko8840s48gc0gsoocgsoc"
EOD;

    }

}


 