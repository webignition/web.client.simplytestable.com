<?php

namespace SimplyTestable\WebClientBundle\Tests\Model\Postmark\Response;

use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;

class SuccessTest extends ResponseTest {   
    
    protected function getJsonResponse() {
        return '{"To":"foo@example.com","SubmittedAt":"2014-02-20T05:51:07.4670731-05:00","MessageID":"19cf44c7-f3a4-47a7-8680-47a8c5c5c5b1","ErrorCode":0,"Message":"OK"}';
    }      
    
    public function testIsNotError() {
        $this->assertFalse($this->response->isError());
    }
    
    public function testErrorCodeIsNull() {
        $this->assertNull($this->response->getErrorCode());
    } 
    
    public function testMessageIsCorrect() {
        $this->assertEquals(PostmarkResponse::NON_ERROR_RESPONSE_MESSAGE, $this->response->getMessage());
    }
    
}