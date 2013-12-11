<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\GetTestTypeOptions\Features;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\AbstractTestOptionsTest;

class HttpAuthenticationFeatureTest extends AbstractTestOptionsTest {    

    public function testRequiresAuthenticationOffByDefault() {
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();
        $this->assertFalse(isset($httpAuthenticationFeatureOptions['requires-authentication']));
    }    
    
    public function testUsernameBlankByDefault() {
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();
        $this->assertFalse(isset($httpAuthenticationFeatureOptions['username']));
    }  
    
    public function testPasswordBlankByDefault() {
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();
        $this->assertFalse(isset($httpAuthenticationFeatureOptions['password']));
    }  
    
    public function testGetRequiresAuthenticationFromRequest() {
        $this->getRequestData()->set('authentication', '1');
        
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();        
        $this->assertEquals(1, $httpAuthenticationFeatureOptions['authentication']);
    }
    
    
    public function testGetUsernameFromRequest() {
        $this->getRequestData()->set('authentication-username', 'foo');
        
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();                
        $this->assertEquals('foo', $httpAuthenticationFeatureOptions['authentication-username']);
    }
    
    
    public function testGetPasswordFromRequest() {
        $this->getRequestData()->set('authentication-password', 'bar');
        
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();        
        $this->assertEquals('bar', $httpAuthenticationFeatureOptions['authentication-password']);
    }
    
    
    /**
     * 
     * @return array
     */
    private function getHttpAuthenticationFeatureOptions() {
        return $this->getTestOptions()->getFeatureOptions('http-authentication');
    }    
}
