<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions\Adapter;

use SimplyTestable\WebClientBundle\Model\TestOptions;

class Request {
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $requestData = array();
    
    
    /**
     *
     * @var array
     */
    private $availableTaskTypes = array();
    
    
    /**
     *
     * @var array
     */
    private $namesAndDefaultValues= array();
    
    
    /**
     *
     * @var array
     */
    private $invertOptionKeys = array();
    
    /**
     *
     * @var boolean
     */
    private $invertInvertableOptions = false;

    
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
     * @param array $namesAndDefaultValues
     */
    public function setNamesAndDefaultValues($namesAndDefaultValues) {
        $this->namesAndDefaultValues = $namesAndDefaultValues;
    }
    
    
    /**
     * 
     * @param array $availableTaskTypes
     */
    public function setAvailableTaskTypes($availableTaskTypes) {
        $this->availableTaskTypes = $availableTaskTypes;
    }
    

    /**
     * 
     * @param array $invertOptionKeys
     */
    public function setInvertOptionKeys($invertOptionKeys) {
        $this->invertOptionKeys = $invertOptionKeys;
    } 
    
    
    /**
     * 
     * @param boolean $invertInvertableOptions
     */
    public function setInvertInvertableOptions($invertInvertableOptions) {
        $this->invertInvertableOptions = $invertInvertableOptions;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\TestOptions
     */
    public function getTestOptions() {                
        var_dump($this->requestData);
        exit();
        
        if (is_null($this->testOptions)) {            
            $this->populateTestOptionsFromRequestData();
        }
        
        if ($this->invertInvertableOptions) {
            $this->invertInvertableOptions();
        }
        
        return $this->testOptions;
    }
    
    private function invertInvertableOptions() {
        foreach ($this->invertOptionKeys as $invertOptionKey) {
            $taskTypeKey = $this->getTaskTypeKeyFromTaskTypeOption($invertOptionKey);
            $testTypeOptions = $this->testOptions->getTestTypeOptions($taskTypeKey);
            
            if (isset($testTypeOptions[$invertOptionKey])) {
                $testTypeOptions[$invertOptionKey] = ($testTypeOptions[$invertOptionKey]) ? 0 : 1;
            } else {
                $testTypeOptions[$invertOptionKey] = 1;
            }
            
            $this->testOptions->addTestTypeOptions($taskTypeKey, $testTypeOptions);
        }
    }
    
    private function getTaskTypeKeyFromTaskTypeOption($taskTypeOption) {
        foreach ($this->availableTaskTypes as $taskTypeKey => $taskTypeName) {
            if (substr($taskTypeOption, 0, strlen($taskTypeKey)) == $taskTypeKey) {
                return $taskTypeKey;
            }
        }
        
        return null;
    }
    
    
    
    
    private function populateTestOptionsFromRequestData() {
        $this->testOptions = new TestOptions();
        $this->testOptions->setAvailableTaskTypes($this->availableTaskTypes);
       
        $testTypes = $this->parseTestTypes(); 
        
        foreach ($testTypes as $testTypeKey => $testTypeName) {
            $this->testOptions->addTestType($testTypeKey, $testTypeName);
        }
        
        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {            
            $this->testOptions->addTestTypeOptions($testTypeKey, $this->parseTestTypeOptions($testTypeKey));
        }
    }    
    
    
    /**
     * 
     * @return array
     */
    private function parseTestTypes() {        
        $testTypes = array();
        
        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {                                    
            if (filter_var($this->requestData->get($testTypeKey), FILTER_VALIDATE_BOOLEAN)) {
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
            if ($this->requestKeyMatchesTestTypeKey($key, $testTypeKey) && array_key_exists($key, $this->namesAndDefaultValues)) {               
                
                switch (gettype($this->namesAndDefaultValues[$key])) {
                    case 'integer':
                        $testTypeOptions[$key] = (int)$value;
                        break;
                    
                    case 'array':
                        $rawValues = (is_string($value)) ? explode("\n", $value) : $value;
                        $cleanedValues = array();
                        foreach ($rawValues as $rawValue) {
                            $rawValue = trim($rawValue);
                            if ($rawValue != '') {
                                $cleanedValues[] = $rawValue;
                            }
                        }
                        
                        $testTypeOptions[$key] = $cleanedValues;
                        break;
                        
                    default:
                        $testTypeOptions[$key] = $value;
                        break;
                }
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