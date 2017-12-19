<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\GetTestTypeOptions\Features;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\ServiceTest;

class HttpAuthenticationFeatureTest extends ServiceTest {

    public function testRequiresAuthenticationOffByDefault() {
        $this->assertFalse(isset($this->getHttpAuthenticationFeatureOptions()['requires-authentication']));
    }    
    
    public function testUsernameBlankByDefault() {
        $this->assertFalse(isset($this->getHttpAuthenticationFeatureOptions()['username']));
    }  
    
    public function testPasswordBlankByDefault() {
        $this->assertFalse(isset($this->getHttpAuthenticationFeatureOptions()['password']));
    }    
    
    public function testGetUsernameFromRequest() {
        $this->getRequestData()->set('http-auth-username', 'foo');
        $this->assertEquals('foo', $this->getHttpAuthenticationFeatureOptions()['http-auth-username']);
    }
    
    
    public function testGetPasswordFromRequest() {
        $this->getRequestData()->set('http-auth-password', 'bar');
        $this->assertEquals('bar', $this->getHttpAuthenticationFeatureOptions()['http-auth-password']);
    }
    
    
    /**
     * 
     * @return array
     */
    private function getHttpAuthenticationFeatureOptions() {
        return $this->getTestOptions()->getFeatureOptions('http-authentication');
    }    
}
