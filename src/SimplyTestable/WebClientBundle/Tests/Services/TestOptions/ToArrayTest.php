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
                    'domains-to-ignore' => array(),
                    'jslint-option-passfail' => 0,
                    'jslint-option-bitwise' => 0,
                    'jslint-option-continue' => 0,
                    'jslint-option-debug' => 0,
                    'jslint-option-evil' => 0,
                    'jslint-option-eqeq' => 0,
                    'jslint-option-es5' => 0,
                    'jslint-option-forin' => 0,
                    'jslint-option-newcap' => 0,
                    'jslint-option-nomen' => 0,
                    'jslint-option-plusplus' => 0,
                    'jslint-option-regexp' => 0,
                    'jslint-option-undef' => 0,
                    'jslint-option-unparam' => 0,
                    'jslint-option-sloppy' => 0,
                    'jslint-option-stupid' => 0,
                    'jslint-option-sub' => 0,
                    'jslint-option-vars' => 0,
                    'jslint-option-white' => 0,
                    'jslint-option-anon' => 1,
                    'jslint-option-browser' => 1,
                    'jslint-option-devel' => 0,
                    'jslint-option-windows' => 0,
                    'jslint-option-maxerr' => 50,
                    'jslint-option-indent' => 4,
                    'jslint-option-maxlen' => 256,
                    'jslint-option-predef' => array()

                )
            )
        ), $testOptions->__toArray());
    }
}
