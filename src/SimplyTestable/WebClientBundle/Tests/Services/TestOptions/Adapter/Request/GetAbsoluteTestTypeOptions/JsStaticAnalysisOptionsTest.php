<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\GetAbsoluteTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Services\TestOptions\Adapter\Request\ServiceTest;

class JsStaticAnalysisOptionsTest extends ServiceTest {

    public function setUp() {
        parent::setUp();
        $this->getRequestData()->set('js-static-analysis', '1');
        $this->getTaskTypeService()->setUserIsAuthenticated(true);
    }

    public function testJsStaticAnalysisDomainsToIgnoreOne() {
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com');
        $this->assertEquals(
            ['one.example.com'],
            $this->getJsStaticAnalysisAbsoluteTestTypeOptions()['domains-to-ignore']
        );
    }

    public function testJsStaticAnalysisDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');
        $this->assertEquals(
            [
                'one.example.com',
                'two.example.com'
            ],
            $this->getJsStaticAnalysisAbsoluteTestTypeOptions()['domains-to-ignore']
        );
    }
    
    public function testJsStaticAnalysisIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '1');
        $this->assertEquals('1', $this->getJsStaticAnalysisAbsoluteTestTypeOptions()['ignore-common-cdns']);
    }     
    
    public function testJsStaticAnalysisIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '0');
        $this->assertEquals('0', $this->getJsStaticAnalysisAbsoluteTestTypeOptions()['ignore-common-cdns']);
    }  
    
    
    /**
     * 
     * @return array
     */
    private function getJsStaticAnalysisAbsoluteTestTypeOptions() {
        return $this->getTestOptions()->getAbsoluteTestTypeOptions('js-static-analysis', false);
    }  
}
