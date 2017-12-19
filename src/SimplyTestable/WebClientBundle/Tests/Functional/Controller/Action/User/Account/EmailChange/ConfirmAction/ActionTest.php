<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ConfirmAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    protected function getRequestToken() {
        return 'foo';
    }

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/account/';
    }

    protected function getActionMethodArguments() {
        return [];
    }

    public function getRequestPostData() {
        return [
            'token' => $this->getRequestToken()
        ];
    }


    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

    

}


 