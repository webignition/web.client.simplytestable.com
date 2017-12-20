<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Stripe\Event\IndexAction;

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
                'foo' => 'bar',
                'user' => 'user@example.com'
            )
        ));
    }    
    
    public function testUnacceptableEventNameReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'event' => 'foo',
                'user' => 'user@example.com'
            )
        ));
    }
    
    public function testMissingUserReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'event' => 'customer.subscription.created'
            )
        ));
    }    
}
