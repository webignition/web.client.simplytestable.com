<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Stripe\Event\IndexAction;

class ErrorCasesTest extends ActionTest {
    
    public function testEmptyPostDataReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ));
    }
    
    public function testMissingEventNameReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'foo' => 'bar'
            )
        ));
    }    
    
    public function testUnacceptableEventNameReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'event' => 'foo'
            )
        ));
    }
}
