<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

abstract class PostmarkExceptionTest extends ActionTest {

    abstract protected function getPostmarkJsonResponse();

    public function preCall() {
        $this->getMailService()->getSender()->setJsonResponse($this->getPostmarkJsonResponse());
    }

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.0 200',
            'HTTP/1.0 200'
        ];
    }


}


 