<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresValidUser;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresValidUserTest extends OnKernelRequestTest {

    abstract protected function getUserAuthenticateHttpResponse();

    protected function buildEvent() {
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            $this->getUserAuthenticateHttpResponse()
        )));

        return parent::buildEvent();
    }


    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\View\Test\History\IndexController::indexAction';
    }

    protected function getControllerRouteString() {
        return 'view_user_account_index_index';
    }
}