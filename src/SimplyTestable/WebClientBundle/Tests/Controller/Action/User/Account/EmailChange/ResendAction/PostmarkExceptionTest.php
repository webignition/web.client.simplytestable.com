<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\EmailChange\ResendAction;

abstract class PostmarkExceptionTest extends ActionTest {

    abstract protected function getPostmarkJsonResponse();

    public function preCall() {
        $this->getMailService()->getSender()->setJsonResponse($this->getPostmarkJsonResponse());
    }

    protected function getHttpFixtureItems() {
        return [
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


 