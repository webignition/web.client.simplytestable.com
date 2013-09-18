<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\GetTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\AbstractTestOptionsTest;

class GetTestTypeOptionsJsStaticAnalysisOptionsTest extends AbstractTestOptionsTest {    

    public function testJsStaticAnalysisDomainsToIgnoreOne() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        
        $this->assertEquals(array(
            'one.example.com'
        ), $jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']);        
    }

    public function testJsStaticAnalysisDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        
        $this->assertEquals(array(
            'one.example.com',
            'two.example.com'
        ), $jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']);      
    }    
    
    public function testJsStaticAnalysisDomainsToIgnoreUnset() {
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        $this->assertFalse(isset($jsStaticAnalysisOptions['js-static-analysis-domains-to-ignore']));         
    }
    
    public function testJsStaticAnalysisIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '1');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        
        $this->assertEquals('1', $jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']);        
    }     
    
    public function testJsStaticAnalysisIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '0');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        
        $this->assertEquals('0', $jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']);        
    } 
    
    public function testJsStaticAnalysisIgnoreCommonCdnsUnset() {
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisTestTypeOptions();
        $this->assertFalse(isset($jsStaticAnalysisOptions['js-static-analysis-ignore-common-cdns']));         
    }
    
    /**
     * 
     * @return array
     */
    private function getJsStaticAnalysisTestTypeOptions() {
        return $this->getTestOptions()->getTestTypeOptions('js-static-analysis');
    }  
}
