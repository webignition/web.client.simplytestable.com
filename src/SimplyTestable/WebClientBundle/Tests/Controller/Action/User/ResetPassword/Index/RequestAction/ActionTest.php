<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\ResetPassword\Index\RequestAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    const EMAIL = 'user@example.com';

    abstract protected function getExpectedFlashValues();

    protected function getRequestEmailAddress() {
        return self::EMAIL;
    }

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return 'http://localhost/reset-password/?email=' . urlencode(self::EMAIL);
    }

    protected function getActionMethodArguments() {
        return [];
    }

    protected function getRequestPostData() {
        return [
            'email' => $this->getRequestEmailAddress()
        ];
    }

    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }

    

}


 