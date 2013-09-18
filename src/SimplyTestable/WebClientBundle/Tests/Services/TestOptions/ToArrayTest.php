<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

class ToArrayTest extends BaseTestCase {   

    public function testHtmlValidationOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('html-validation', 'HTML validation');
        
        $this->assertEquals(array(
            'test-types' => array(
                'HTML validation'
            ),
            'test-type-options' => array(
            )
        ), $testOptions->__toArray());
    }
    
    
    public function testCssValidationOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('css-validation','CSS validation');
        
        $this->assertEquals(array(
            'test-types' => array(
                'CSS validation'
            ),
            'test-type-options' => array(
            )
        ), $testOptions->__toArray());
    } 
    
    
    public function testJsStaticAnalysisOnly() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('js-static-analysis','JS static analysis');
        
        $this->assertEquals(array(
            'test-types' => array(
                'JS static analysis'
            ),
            'test-type-options' => array( 
            )
        ), $testOptions->__toArray());
    }
}
