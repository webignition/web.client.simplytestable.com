<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\GetTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\AbstractTestOptionsTest;

class GetAbsolutetestTypeOptionsJsStaticAnalysisOptionsTest extends AbstractTestOptionsTest {    

    public function testJsStaticAnalysisDomainsToIgnoreOne() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com'), $jsStaticAnalysisOptions['domains-to-ignore']);        
    }

    public function testJsStaticAnalysisDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisAbsoluteTestTypeOptions();
        
        $this->assertEquals(array('one.example.com','two.example.com'), $jsStaticAnalysisOptions['domains-to-ignore']);        
    }
    
    public function testJsStaticAnalysisIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '1');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisAbsoluteTestTypeOptions();
        
        $this->assertEquals('1', $jsStaticAnalysisOptions['ignore-common-cdns']);        
    }     
    
    public function testJsStaticAnalysisIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '0');
        
        $jsStaticAnalysisOptions = $this->getJsStaticAnalysisAbsoluteTestTypeOptions();
        
        $this->assertEquals('0', $jsStaticAnalysisOptions['ignore-common-cdns']);        
    }  
    
    
    /**
     * 
     * @return array
     */
    private function getJsStaticAnalysisAbsoluteTestTypeOptions() {
        return $this->getTestOptions()->getAbsoluteTestTypeOptions('js-static-analysis', false);
    }  
}
