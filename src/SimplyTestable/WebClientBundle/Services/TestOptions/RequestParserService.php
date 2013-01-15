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
            $this->testOptions->addTestTypeOptions($testTypeKey, $this->parseTestTypeOptions($testTypeKey));
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
    
    
    /**
     * 
     * @param string $testTypeKey
     * @return array
     */
    private function parseTestTypeOptions($testTypeKey) {        
        $testTypeOptions = array();
        
        foreach ($this->requestData as $key => $value) {            
            if ($this->requestKeyMatchesTestTypeKey($key, $testTypeKey)) {
                $testTypeOptions[$key] = $value;
            }
        }
        
        return $testTypeOptions;
    }
    
    
    /**
     * 
     * @param string $requestKey
     * @param string $testTypeKey
     * @return boolean
     */
    private function requestKeyMatchesTestTypeKey($requestKey, $testTypeKey) {
        if ($requestKey == $testTypeKey) {
            return false;
        }
        
        return substr($requestKey, 0, strlen($testTypeKey)) == $testTypeKey;
    }
        
}