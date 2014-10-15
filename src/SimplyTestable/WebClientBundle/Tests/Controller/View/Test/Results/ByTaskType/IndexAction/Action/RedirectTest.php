<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\ByTaskType\IndexAction\Action;

abstract class RedirectTest extends ActionTest {

    abstract protected function getExpectedResponseLocation();

    protected function getExpectedResponseStatusCode() {
        return 302;
    }

    public function testResponseHasLocationHeader() {
        $this->assertTrue($this->response->headers->has('location'));
    }

    public function testResponseLocation() {
        $this->assertEquals($this->response->headers->get('location'), $this->getExpectedResponseLocation());
    }
}