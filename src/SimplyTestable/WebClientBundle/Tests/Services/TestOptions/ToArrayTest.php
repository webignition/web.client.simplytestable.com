<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

class ToArrayTest extends BaseTestCase {   

    public function testHtmlValidationOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->assertEquals(array(
            'test-types' => array(
                'HTML validation'
            ),
            'test-type-options' => array(
                'HTML validation' => array()
            )
        ), $testOptions->__toArray());
    }
    
    
    public function testCssValidationOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('CSS validation');
        
        $this->assertEquals(array(
            'test-types' => array(
                'CSS validation'
            ),
            'test-type-options' => array(
                'CSS validation' => array(
                    'ignore-warnings' => 0,
                    'ignore-common-cdns' => 0,
                    'vendor-extensions' => 'warn',
                    'domains-to-ignore' => array()                    
                )
            )
        ), $testOptions->__toArray());
    } 
    
    
    public function testJsStaticAnalysisOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('JS static analysis');
        
        $this->assertEquals(array(
            'test-types' => array(
                'JS static analysis'
            ),
            'test-type-options' => array(
                'JS static analysis' => array(
                    'ignore-common-cdns' => 0,
                    'domains-to-ignore' => array()                    
                )
            )
        ), $testOptions->__toArray());
    }
}
