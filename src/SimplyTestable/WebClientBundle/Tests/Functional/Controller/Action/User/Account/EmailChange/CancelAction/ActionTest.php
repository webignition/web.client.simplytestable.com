<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\CancelAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/account/';
    }

    protected function getActionMethodArguments() {
        return [];
    }

    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

    

}


 