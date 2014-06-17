<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\GetAbsoluteTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\ServiceTest;

class CssValidationOptionsTest extends ServiceTest {

    public function setUp() {
        parent::setUp();
        $this->getRequestData()->set('css-validation', '1');
    }

    public function testCssValidationIgnoreWarningsTrue() {
        $this->getRequestData()->set('css-validation-ignore-warnings', '1');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['ignore-warnings']);
    }    
    
    public function testCssValidationIgnoreWarningsFalse() {
        $this->getRequestData()->set('css-validation-ignore-warnings', '0');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['ignore-warnings']);
    }    
    
    public function testCssValidationVendorExtensionsIgnore() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'ignore');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('ignore', $cssValidationOptions['vendor-extensions']);
    }    
    
    public function testCssValidationVendorExtensionsWarn() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'warn');
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('warn', $cssValidationOptions['vendor-extensions']);
    }
    
    public function testCssValidationVendorExtensionsError() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'error');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('error', $cssValidationOptions['vendor-extensions']);
    }
    
    public function testCssValidationDomainsToIgnoreOne() {
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com'), $cssValidationOptions['domains-to-ignore']);        
    }

    public function testCssValidationDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com','two.example.com'), $cssValidationOptions['domains-to-ignore']);        
    }
    
    public function testCssValidationIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '1');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['ignore-common-cdns']);        
    }     
    
    public function testCssValidationIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '0');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['ignore-common-cdns']);        
    }
    

    /**
     * 
     * @return array
     */
    private function getCssValidationAbsoluteTestTypeOptions() {
        return $this->getTestOptions()->getAbsoluteTestTypeOptions('css-validation', false);
    }  
}
