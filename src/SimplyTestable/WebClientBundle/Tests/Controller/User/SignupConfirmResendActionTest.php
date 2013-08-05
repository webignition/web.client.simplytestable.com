<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class SignupConfirmResendActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    } 
    
    
    public function testWithNonExistentUser() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmResendAction')->signupConfirmResendAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-user', $this->container->get('session')->getFlash('token_resend_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());
    } 
    
    
    public function testWithInvalidAdminCredentials() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $response = $this->getUserController('signupConfirmResendAction')->signupConfirmResendAction('user@example.com');
            $this->assertEquals(302, $response->getStatusCode());
            $this->fail('CoreApplicationAdminRequestException  401 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };          
    }
    
    
    public function testSuccess() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmResendAction')->signupConfirmResendAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('sent', $this->container->get('session')->getFlash('token_resend_confirmation')); 
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());        
    }
    

}


 