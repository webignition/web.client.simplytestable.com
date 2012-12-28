<?php

namespace SimplyTestable\WebClientBundle\Tests\Command;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

class TestOptionsRequestParserServiceGetTestTypeOptionsTest extends BaseTestCase {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $requestData;
    
    public function setUp() {
        parent::setUp();
        $this->requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
    }

    public function testCssValidationIgnoreWarningsTrue() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-ignore-warnings', '1');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['css-validation-ignore-warnings']);
    }    
    
    public function testCssValidationIgnoreWarningsFalse() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-ignore-warnings', '0');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['css-validation-ignore-warnings']);
    }
    
    public function testCssValidationIgnoreWarningsUnset() {        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertFalse(isset($cssValidationOptions['css-validation-ignore-warnings']));
    }    
    
    public function testCssValidationVendorExtensionsIgnore() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-vendor-extensions', 'ignore');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('ignore', $cssValidationOptions['css-validation-vendor-extensions']);
    }    
    
    public function testCssValidationVendorExtensionsWarn() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-vendor-extensions', 'warn');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('warn', $cssValidationOptions['css-validation-vendor-extensions']);
    }
    
    public function testCssValidationVendorExtensionsError() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-vendor-extensions', 'error');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('error', $cssValidationOptions['css-validation-vendor-extensions']);
    }  
    
    public function testCssValidationVendorExtensionsUnset() {        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        $this->assertFalse(isset($cssValidationOptions['css-validation-vendor-extensions']));        
    } 
    
    
    public function testCssValidationDomainsToIgnoreOne() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-domains-to-ignore', 'one.example.com');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('one.example.com', $cssValidationOptions['css-validation-domains-to-ignore']);        
    }

    public function testCssValidationDomainsToIgnoreOneTwo() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('one.example.com'."\r\n".'two.example.com', $cssValidationOptions['css-validation-domains-to-ignore']);        
    }    
    
    public function testCssValidationDomainsToIgnoreUnset() {
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        $this->assertFalse(isset($cssValidationOptions['css-validation-domains-to-ignore']));         
    }
    
    public function testCssValidationIgnoreCommonCdnsTrue() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-ignore-common-cdns', '1');
        
        $cssValidationOptions = $this->getCssValidationTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['css-validation-ignore-common-cdns']);        
    }     
    
    public function testCssValidationIgnoreCommonCdnsFalse() {
        $this->requestData->set('css-validation', '1');
        $this->requestData->set('css-validation-ignore-common-cdns', '0');
        
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
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestOptions
     */
    private function getTestOptions() {
        $this->getTestOptionsRequestParserService()->setRequestData($this->requestData);
        return $this->getTestOptionsRequestParserService()->getTestOptions();        
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    } 
    
/**
 * -css-validation-ignore-warnings
 * -css-validation-vendor-extensions
 * css-validation-domains-to-ignore
 * css-validation-ignore-common-cdns
 * js-static-analysis-ignore-common-cdns
 * js-static-analysis-domains-to-ignore
 */
    
    
/**

class Symfony\Component\HttpFoundation\ParameterBag#7 (1) {
  protected $parameters =>
  array(6) {
    'website' =>
    string(15) "webignition.net"
    'css-validation' =>
    string(1) "1"
    'css-validation-vendor-extensions' =>
    string(4) "warn"
    'css-validation-domains-to-ignore' =>
    string(0) ""
    'js-static-analysis-ignore-common-cdns' =>
    string(1) "1"
    'js-static-analysis-domains-to-ignore' =>
    string(0) ""
  }
}
 * 
class Symfony\Component\HttpFoundation\ParameterBag#7 (1) {
  protected $parameters =>
  array(6) {
    'website' =>
    string(15) "webignition.net"
    'css-validation' =>
    string(1) "1"
    'css-validation-vendor-extensions' =>
    string(4) "warn"
    'css-validation-domains-to-ignore' =>
    string(0) ""
    'js-static-analysis-ignore-common-cdns' =>
    string(1) "1"
    'js-static-analysis-domains-to-ignore' =>
    string(0) ""
  }
}
 * 
class Symfony\Component\HttpFoundation\ParameterBag#37 (1) {
  protected $parameters =>
  array(1) {
    'css-validation' =>
    string(1) "1"
  }
}

 */    
}
