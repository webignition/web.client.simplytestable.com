<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions;

use SimplyTestable\WebClientBundle\Model\TestOptions;

class RequestParserService {    
    
    private $testTypeMap = array(
        'html-validation' => 'HTML validation',
        'css-validation' => 'CSS validation',
        'js-static-analysis' => 'JS static analysis'
    );    
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $requestData = array();
    
    
    /**
     *
     * @var TestOptions
     */
    private $testOptions = null;
    
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestData
     */
    public function setRequestData(\Symfony\Component\HttpFoundation\ParameterBag $requestData) {
        $this->requestData = $requestData;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestOptions
     */
    public function getTestOptions() {        
        if (is_null($this->testOptions)) {            
            $this->populateTestOptionsFromRequestData();
        }
        
        return $this->testOptions;
    }
    
    
    
    private function populateTestOptionsFromRequestData() {
        $this->testOptions = new TestOptions();
       
        $testTypes = $this->parseTestTypes();
        foreach ($testTypes as $testTypeKey => $testTypeName) {
            $this->testOptions->addTestType($testTypeName);
        }
        
    }    
    
    
    /**
     * 
     * @return array
     */
    private function parseTestTypes() {        
        $testTypes = array();
        
        foreach ($this->testTypeMap as $testTypeKey => $testTypeName) {            
            if ($this->requestData->get($testTypeKey) === "1") {
                $testTypes[$testTypeKey] = $testTypeName;
            }
        }              
        
        return $testTypes;
    }    
        
}