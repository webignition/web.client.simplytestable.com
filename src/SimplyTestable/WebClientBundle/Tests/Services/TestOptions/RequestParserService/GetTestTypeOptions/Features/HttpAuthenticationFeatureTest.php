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
    
    public function testGetUsernameFromRequest() {
        $this->getRequestData()->set('http-auth-username', 'foo');
        
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();                
        $this->assertEquals('foo', $httpAuthenticationFeatureOptions['http-auth-username']);
    }
    
    
    public function testGetPasswordFromRequest() {
        $this->getRequestData()->set('http-auth-password', 'bar');
        
        $httpAuthenticationFeatureOptions = $this->getHttpAuthenticationFeatureOptions();        
        $this->assertEquals('bar', $httpAuthenticationFeatureOptions['http-auth-password']);
    }
    
    
    /**
     * 
     * @return array
     */
    private function getHttpAuthenticationFeatureOptions() {
        return $this->getTestOptions()->getFeatureOptions('http-authentication');
    }    
}
