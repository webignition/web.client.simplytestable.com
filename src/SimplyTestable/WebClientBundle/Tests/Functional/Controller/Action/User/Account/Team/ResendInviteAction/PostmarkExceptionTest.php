<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\Team\ResendInviteAction;

abstract class PostmarkExceptionTest extends ActionTest {

    abstract protected function getPostmarkJsonResponse();

    public function preCall() {
        $this->getUserService()->setUser($this->makeUser());
        $this->getMailService()->getSender()->setJsonResponse($this->getPostmarkJsonResponse());
    }

    protected function getRequestPostData() {
        return [
            'user' => 'invitee@example.com'
        ];
    }

    protected function getHttpFixtureItems() {
        return [
            $this->getInviteRequestHttpFixture(),
            'HTTP/1.1 200',
            'HTTP/1.1 200',
            'HTTP/1.1 200'
        ];
    }


    private function getInviteRequestHttpFixture() {
        return <<<EOD
HTTP/1.1 200 OK
Content-Type: application/json

{"team":"Foo","user":"invitee@example.com","token":"ada90a0a98f6441267ae0777d4ef9f50"}
EOD;

    }


}


 