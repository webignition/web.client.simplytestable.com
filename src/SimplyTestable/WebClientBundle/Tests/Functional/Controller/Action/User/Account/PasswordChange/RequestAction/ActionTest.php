<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\PasswordChange\RequestAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    protected function getRequestCurrentPassword() {
        return 'password';
    }

    protected function getRequestNewPassword() {
        return 'new-password';
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
            'current-password' => $this->getRequestCurrentPassword(),
            'new-password' => $this->getRequestNewPassword()
        ];
    }


    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

    

}


 