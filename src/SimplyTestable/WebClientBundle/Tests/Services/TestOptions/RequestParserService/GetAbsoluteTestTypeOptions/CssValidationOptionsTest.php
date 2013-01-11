<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\GetAbsoluteTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\AbstractTestOptionsTest;

class TestOptionsRequestParserServicesGetAbsolutetestTypeOptionsCssValidationOptionsTest extends AbstractTestOptionsTest {

    public function testCssValidationIgnoreWarningsTrue() {        
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-warnings', '1');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['ignore-warnings']);
    }    
    
    public function testCssValidationIgnoreWarningsFalse() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-warnings', '0');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['ignore-warnings']);
    }
    
    public function testCssValidationIgnoreWarningsUnset() {        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['ignore-warnings']);
    }    
    
    public function testCssValidationVendorExtensionsIgnore() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'ignore');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('ignore', $cssValidationOptions['vendor-extensions']);
    }    
    
    public function testCssValidationVendorExtensionsWarn() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'warn');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('warn', $cssValidationOptions['vendor-extensions']);
    }
    
    public function testCssValidationVendorExtensionsError() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-vendor-extensions', 'error');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('error', $cssValidationOptions['vendor-extensions']);
    }  
    
    public function testCssValidationVendorExtensionsUnset() {        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        $this->assertEquals('warn', $cssValidationOptions['vendor-extensions']);;        
    }
    
    public function testCssValidationDomainsToIgnoreOne() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com'), $cssValidationOptions['domains-to-ignore']);        
    }

    public function testCssValidationDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com','two.example.com'), $cssValidationOptions['domains-to-ignore']);        
    }    
    
    public function testCssValidationDomainsToIgnoreUnset() {
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();        
        $this->assertEquals(array(), $cssValidationOptions['domains-to-ignore']);      
    }
    
    public function testCssValidationIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '1');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('1', $cssValidationOptions['ignore-common-cdns']);        
    }     
    
    public function testCssValidationIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('css-validation', '1');
        $this->getRequestData()->set('css-validation-ignore-common-cdns', '0');
        
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $cssValidationOptions['ignore-common-cdns']);        
    } 
    
    public function testCssValidationIgnoreCommonCdnsUnset() {
        $cssValidationOptions = $this->getCssValidationAbsoluteTestTypeOptions();
        $this->assertEquals('0', $cssValidationOptions['ignore-common-cdns']);       
    }
    
    
    
    
    
//    
//
//    public function testJsStaticAnalysisDomainsToIgnoreOne() {
//        $this->getRequestData()->set('js-static-analysis', '1');
//        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com');
//        
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        
//        $this->assertEquals('one.example.com', $jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']);        
//    }
//
//    public function testJsStaticAnalysisDomainsToIgnoreOneTwo() {
//        $this->getRequestData()->set('js-static-analysis', '1');
//        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
//        
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        
//        $this->assertEquals('one.example.com'."\r\n".'two.example.com', $jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']);        
//    }    
//    
//    public function testJsStaticAnalysisDomainsToIgnoreUnset() {
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        $this->assertFalse(isset($jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']));         
//    }
//    
//    public function testJsStaticAnalysisIgnoreCommonCdnsTrue() {
//        $this->getRequestData()->set('js-static-analysis', '1');
//        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '1');
//        
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        
//        $this->assertEquals('1', $jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']);        
//    }     
//    
//    public function testJsStaticAnalysisIgnoreCommonCdnsFalse() {
//        $this->getRequestData()->set('js-static-analysis', '1');
//        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '0');
//        
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        
//        $this->assertEquals('0', $jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']);        
//    } 
//    
//    public function testJsStaticAnalysisIgnoreCommonCdnsUnset() {
//        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
//        $this->assertFalse(isset($jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']));         
//    }
        
    
//    /**
//     * 
//     * @return array
//     */
//    private function getCssValidationTestTypeOptions() {
//        return $this->getTestOptions()->getTestTypeOptions('css-validation');
//    }
//    
//    /**
//     * 
//     * @return array
//     */
//    private function getJsStaticAnalysisTestTypeOptions() {
//        return $this->getTestOptions()->getTestTypeOptions('js-static-analysis');
//    }  
    

    /**
     * 
     * @return array
     */
    private function getCssValidationAbsoluteTestTypeOptions() {
        return $this->getTestOptions()->getAbsoluteTestTypeOptions('css-validation', false);
    }
//    
//    
//    /**
//     * 
//     * @return \SimplyTestable\WebClientBundle\Model\TestOptions
//     */
//    private function getTestOptions() {
//        $this->getTestOptionsRequestParserService()->setRequestData($this->requestData);
//        return $this->getTestOptionsRequestParserService()->getTestOptions();        
//    }
//    
//    
//    /**
//     *
//     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
//     */
//    private function getTestOptionsRequestParserService() {
//        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
//    } 
    
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
