<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions\RequestParserService\HasTestTypeTest;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

class HasTestTypeTest extends BaseTestCase {   

    public function testHas000() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '0');
        $requestData->set('css-validation', '0');
        $requestData->set('js-static-analysis', '0');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertFalse($testOptions->hasTestType('HTML validation'));
        $this->assertFalse($testOptions->hasTestType('CSS validation'));
        $this->assertFalse($testOptions->hasTestType('JS static analysis'));
    }
    
    public function testHas001() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '0');
        $requestData->set('css-validation', '0');
        $requestData->set('js-static-analysis', '1');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertFalse($testOptions->hasTestType('HTML validation'));
        $this->assertFalse($testOptions->hasTestType('CSS validation'));
        $this->assertTrue($testOptions->hasTestType('JS static analysis'));
    }     
  
    public function testHas010() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '0');
        $requestData->set('css-validation', '1');
        $requestData->set('js-static-analysis', '0');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertFalse($testOptions->hasTestType('HTML validation'));
        $this->assertTrue($testOptions->hasTestType('CSS validation'));
        $this->assertFalse($testOptions->hasTestType('JS static analysis'));
    }     
  
    public function testHas011() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '0');
        $requestData->set('css-validation', '1');
        $requestData->set('js-static-analysis', '1');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertFalse($testOptions->hasTestType('HTML validation'));
        $this->assertTrue($testOptions->hasTestType('CSS validation'));
        $this->assertTrue($testOptions->hasTestType('JS static analysis'));
    }     

    public function testHas100() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '1');
        $requestData->set('css-validation', '0');
        $requestData->set('js-static-analysis', '0');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertTrue($testOptions->hasTestType('HTML validation'));
        $this->assertFalse($testOptions->hasTestType('CSS validation'));
        $this->assertFalse($testOptions->hasTestType('JS static analysis'));
    }    
    
    public function testHas101() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '1');
        $requestData->set('css-validation', '0');
        $requestData->set('js-static-analysis', '1');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertTrue($testOptions->hasTestType('HTML validation'));
        $this->assertFalse($testOptions->hasTestType('CSS validation'));
        $this->assertTrue($testOptions->hasTestType('JS static analysis'));
    }     
  
    public function testHas110() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '1');
        $requestData->set('css-validation', '1');
        $requestData->set('js-static-analysis', '0');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertTrue($testOptions->hasTestType('HTML validation'));
        $this->assertTrue($testOptions->hasTestType('CSS validation'));
        $this->assertFalse($testOptions->hasTestType('JS static analysis'));
    }     
  
    public function testHas111() {
        $requestData = new \Symfony\Component\HttpFoundation\ParameterBag();
        $requestData->set('html-validation', '1');
        $requestData->set('css-validation', '1');
        $requestData->set('js-static-analysis', '1');
        
        $this->getTestOptionsRequestParserService()->setRequestData($requestData);
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        $this->assertTrue($testOptions->hasTestType('HTML validation'));
        $this->assertTrue($testOptions->hasTestType('CSS validation'));
        $this->assertTrue($testOptions->hasTestType('JS static analysis'));
    }
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    }    
}
