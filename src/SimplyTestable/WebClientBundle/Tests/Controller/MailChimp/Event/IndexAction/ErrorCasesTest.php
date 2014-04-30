<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\MailChimp\Event\IndexAction;

class ErrorCasesTest extends ActionTest {
    
    public function testEmptyPostDataReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ));
    }
    
    public function testMissingEventTypeReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'foo' => 'bar'
            )
        ));
    }    
    
    public function testUnacceptableEventTypeReturns400() {
        $this->performActionTest(array(
            'statusCode' => 400
        ), array(
            'postData' => array(
                'type' => 'foo'
            )
        ));
    } 
}
