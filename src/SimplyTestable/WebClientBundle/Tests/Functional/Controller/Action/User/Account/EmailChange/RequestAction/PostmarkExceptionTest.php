<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\RequestAction;

abstract class PostmarkExceptionTest extends ActionTest {

    abstract protected function getPostmarkJsonResponse();

    public function preCall() {
        $this->getMailService()->getSender()->setJsonResponse($this->getPostmarkJsonResponse());
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.1 200',
            $this->getEmailChangeRequestHttpFixture()
        ];
    }


    private function getEmailChangeRequestHttpFixture() {
        return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

{"new_email":"new-user@example.com","token":"foo"}
EOD;

    }


}


 