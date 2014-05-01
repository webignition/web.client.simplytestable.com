<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\MailChimp\Event\IndexAction;

class ErrorCasesTest extends ActionTest {
    
//    public function testEmptyPostDataReturns400() {
//        $this->performActionTest(array(
//            'statusCode' => 400
//        ));
//    }
//    
//    public function testMissingEventTypeReturns400() {
//        $this->performActionTest(array(
//            'statusCode' => 400
//        ), array(
//            'postData' => array(
//                'foo' => 'bar'
//            )
//        ));
//    }    
//    
//    public function testUnacceptableEventTypeReturns400() {
//        $this->performActionTest(array(
//            'statusCode' => 400
//        ), array(
//            'postData' => array(
//                'type' => 'foo'
//            )
//        ));
//    }
    
    
    public function testUnknownListIdReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'subscribe',
                'data' => array(
                    'list_id' => 'foo',
                    'email' => 'user@example.com'
                )
            )
        ));        
    }
    
    public function testNoEventDataReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'subscribe'
            )
        ));        
    }    
}
