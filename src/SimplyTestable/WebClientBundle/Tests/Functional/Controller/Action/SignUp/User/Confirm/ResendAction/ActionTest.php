<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\SignUp\User\Confirm\ResendAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return '/signup/confirm/user@example.com/';
    }

    protected function getActionMethodArguments() {
        return [
            'email' => 'user@example.com'
        ];
    }

    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

}


 