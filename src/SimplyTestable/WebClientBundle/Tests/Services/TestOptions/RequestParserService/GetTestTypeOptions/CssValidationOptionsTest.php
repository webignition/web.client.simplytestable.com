<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\GetTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\AbstractTestOptionsTest;

class GetTestTypeOptionsCssValidationOptionsTest extends AbstractTestOptionsTest {    

    public function testCssValidationIgnoreWarningsTrue() {        
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-warnings', '1');        
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['css-validation-ignore-warnings']);
    }    
    
    public function testCssValidationIgnoreWarningsFalse() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-warnings', '0');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();        
        
        $this->assertEquals('0', $cssValidationOptions['css-validation-ignore-warnings']);
    }
    
    public function testCssValidationIgnoreWarningsUnset() {        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertFalse(isset($cssValidationOptions['css-validation-ignore-warnings']));
    }    
    
    public function testCssValidationVendorExtensionsIgnore() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'ignore');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('ignore', $cssValidationOptions['css-validation-vendor-extensions']);
    }    
    
    public function testCssValidationVendorExtensionsWarn() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'warn');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('warn', $cssValidationOptions['css-validation-vendor-extensions']);
    }
    
    public function testCssValidationVendorExtensionsError() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'error');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('error', $cssValidationOptions['css-validation-vendor-extensions']);
    }  
    
    public function testCssValidationVendorExtensionsUnset() {        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        $this->assertFalse(isset($cssValidationOptions['css-validation-vendor-extensions']));        
    } 
    
    
    public function testCssValidationDomainsToIgnoreOne() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals(array('one.example.com'), $cssValidationOptions['css-validation-domains-to-ignore']);        
    }

    public function testCssValidationDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals(array(
            'one.example.com',
            'two.example.com'
        ), $cssValidationOptions['css-validation-domains-to-ignore']);        
    }    
    
    public function testCssValidationDomainsToIgnoreUnset() {
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        $this->assertFalse(isset($cssValidationOptions['css-validation-domains-to-ignore']));         
    }
    
    public function testCssValidationIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '1');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['css-validation-ignore-common-cdns']);        
    }     
    
    public function testCssValidationIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '0');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['css-validation-ignore-common-cdns']);        
    } 
    
    public function testCssValidationIgnoreCommonCdnsUnset() {
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        $this->assertFalse(isset($cssValidationOptions['css-validation-ignore-common-cdns']));         
    }
    
    
    /**
     * 
     * @return array
     */
    private function getCssValidationTestTypeOptions() {        
        return $this->getTestOptions()->getTestTypeOptions('css-validation');
    }    
}
