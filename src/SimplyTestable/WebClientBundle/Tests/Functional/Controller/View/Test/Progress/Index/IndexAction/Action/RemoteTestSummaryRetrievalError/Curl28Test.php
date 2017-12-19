<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Progress\Index\IndexAction\Action\RemoteTestSummaryRetrievalError;

class Curl28Test extends ActionTest {

    protected function preCall() {
        $this->setExpectedControllerExceptionClass('Guzzle\Http\Exception\CurlException');
    }

    protected function getHttpFixtureItems() {
        return array(
            'CURL/28'
        );
    }

    public function testCurlCode() {
        $this->assertEquals(28, $this->controllerException->getErrorNo());
    }

}