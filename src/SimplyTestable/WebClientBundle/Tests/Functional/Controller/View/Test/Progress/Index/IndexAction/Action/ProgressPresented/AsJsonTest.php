<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Progress\Index\IndexAction\Action\ProgressPresented;

class AsJsonTest extends ActionTest {

    protected function getExpectedResponseStatusCode() {
        return 200;
    }

    protected function getRequestHeaders() {
        return ['accept' => 'application/json'];
    }

}