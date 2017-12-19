<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\GetTestTypeOptions;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\TestOptions\Adapter\Request\ServiceTest;

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
            $this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-domains-to-ignore']
        );
    }

    public function testJsStaticAnalysisDomainsToIgnoreOneTwo() {
        $this->getRequestData()->set('js-static-analysis-domains-to-ignore', 'one.example.com'."\r\n".'two.example.com');

        $this->assertEquals(
            [
                'one.example.com',
                'two.example.com'
            ],
            $this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-domains-to-ignore']
        );
    }    
    
    public function testJsStaticAnalysisDomainsToIgnoreUnset() {
        $this->assertFalse(isset($this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-domains-to-ignore']));
    }
    
    public function testJsStaticAnalysisIgnoreCommonCdnsTrue() {
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '1');
        $this->assertEquals('1', $this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-ignore-common-cdns']);
    }     
    
    public function testJsStaticAnalysisIgnoreCommonCdnsFalse() {
        $this->getRequestData()->set('js-static-analysis-ignore-common-cdns', '0');
        $this->assertEquals('0', $this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-ignore-common-cdns']);
    } 
    
    public function testJsStaticAnalysisIgnoreCommonCdnsUnset() {
        $this->assertFalse(isset($this->getJsStaticAnalysisTestTypeOptions()['js-static-analysis-ignore-common-cdns']));
    }
    
    /**
     * 
     * @return array
     */
    private function getJsStaticAnalysisTestTypeOptions() {
        return $this->getTestOptions()->getTestTypeOptions('js-static-analysis');
    }  
}
