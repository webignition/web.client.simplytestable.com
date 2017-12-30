<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\GetAbsoluteTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\ServiceTest;

class CssValidationOptionsTest extends ServiceTest {

    protected function setUp() {
        parent::setUp();
        $this->getRequestData()->set('css-validation', '1');
    }

    public function testCssValidationIgnoreWarningsTrue() {
        $this->getRequestData()->set('css-validation-ignore-warnings', '1');
        $this->assertEquals('1', $this->getCssValidationAbsoluteTestTypeOptions()['ignore-warnings']);
    }    
    
    public function testCssValidationIgnoreWarningsFalse() {
        $this->getRequestData()->set('css-validation-ignore-warnings', '0');
        $this->assertEquals('0', $this->getCssValidationAbsoluteTestTypeOptions()['ignore-warnings']);
    }    
    
    public function testCssValidationVendorExtensionsIgnore() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'ignore');
        $this->assertEquals('ignore', $this->getCssValidationAbsoluteTestTypeOptions()['vendor-extensions']);
    }    
    
    public function testCssValidationVendorExtensionsWarn() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'warn');
        $this->assertEquals('warn', $this->getCssValidationAbsoluteTestTypeOptions()['vendor-extensions']);
    }
    
    public function testCssValidationVendorExtensionsError() {
        $this->getRequestData()->set('css-validation-vendor-extensions', 'error');
        $this->assertEquals('error', $this->getCssValidationAbsoluteTestTypeOptions()['vendor-extensions']);
    }
    
    public function testCssValidationDomainsToIgnoreOne() {
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com');
        $this->assertEquals(
            ['one.example.com'],
            $this->getCssValidationAbsoluteTestTypeOptions()['domains-to-ignore']
        );
    }

    public function testCssValidationDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        $this->assertEquals(
            [
                'one.example.com',
                'two.example.com'
            ],
            $this->getCssValidationAbsoluteTestTypeOptions()['domains-to-ignore']
        );
    }
    
    public function testCssValidationIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '1');
        $this->assertEquals('1', $this->getCssValidationAbsoluteTestTypeOptions()['ignore-common-cdns']);
    }     
    
    public function testCssValidationIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '0');
        $this->assertEquals('0', $this->getCssValidationAbsoluteTestTypeOptions()['ignore-common-cdns']);
    }
    

    /**
     * 
     * @return array
     */
    private function getCssValidationAbsoluteTestTypeOptions() {
        return $this->getTestOptions()->getAbsoluteTestTypeOptions('css-validation', false);
    }  
}
