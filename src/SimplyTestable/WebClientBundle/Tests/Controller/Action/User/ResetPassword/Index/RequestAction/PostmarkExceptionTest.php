<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

abstract class PostmarkExceptionTest extends ActionTest {

    abstract protected function getPostmarkJsonResponse();

    public function preCall() {
        $this->getMailService()->getSender()->setJsonResponse($this->getPostmarkJsonResponse());
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


 