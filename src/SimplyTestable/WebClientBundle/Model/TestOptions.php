<?php
namespace SimplyTestable\WebClientBundle\Model;

class TestOptions {      
    
    /**
     *
     * @var array
     */
    private $availableTaskTypes = array();    
    
    private $testTypes = array();    
    private $testTypeOptions = array();    
    
    /**
     * 
     * @param array $availableTaskTypes
     */
    public function setAvailableTaskTypes($availableTaskTypes) {
        $this->availableTaskTypes = $availableTaskTypes;
    }    
    
    /**
     * 
     * @param string $testType
     */
    public function addTestType($testTypeKey, $testType) {
        if (!array_key_exists($testTypeKey, $this->testTypes)) {
            $this->testTypes[$testTypeKey] = $testType;
        }
    }
    
    
    /**
     * 
     * @param string $testType
     * @param array $testTypeOptions
     */
    public function addTestTypeOptions($testType, $testTypeOptions) {        
        $this->testTypeOptions[$testType] = $testTypeOptions;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasTestTypes() {
        return count($this->testTypes) > 0;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getTestTypes() {
        $testTypes = array();
        
        foreach ($this->testTypes as $key => $name) {
            $testTypes[] = $name;
        }
        
        return $testTypes;
    }
    
    
    /**
     * 
     * @param string $testType
     * @return boolean
     */
    public function hasTestTypeOptions($testType) {
        if (!isset($this->testTypeOptions[$testType])) {
            return false;
        }
        
        if (!is_array($this->testTypeOptions[$testType])) {
            return false;
        }
        
        return count($this->testTypeOptions[$testType]) > 0;
    }    
    
    
    /**
     * 
     * @param string $testType
     * @return array
     */
    public function getTestTypeOptions($testType) {
        if ($this->hasTestTypeOptions($testType)) {
            return $this->testTypeOptions[$testType];
        }
        
        return array();
    }
    
    
    /**
     * 
     * @param string $testType
     * @return array
     */
    public function getAbsoluteTestTypeOptions($testType, $useFullOptionKey = true) {
        $absoluteTestTypeOptions = array();
        $testTypeOptions = $this->getTestTypeOptions($testType);
        
        foreach ($testTypeOptions as $optionKey => $optionValue) {
            $key = ($useFullOptionKey) ? $optionKey : str_replace($testType.'-', '', $optionKey); 
            $absoluteTestTypeOptions[$key] = $optionValue;
        }
        
        return $absoluteTestTypeOptions;
    } 
    
    
    /**
     * 
     * @param string $testTypeKey
     * @return string
     */
    public function getNameFromKey($testTypeKey) {        
        foreach ($this->testTypeMap as $key => $value) {            
            if ($testTypeKey == $key) {
                return $value;
            }
        }
        
        return '';
    }
    
    
    
    /**
     * 
     * @return array
     */
    public function getAbsoluteTestTypes() {
        $absoluteTestTypes = array();
        
        foreach ($this->testTypeMap as $testTypeKey => $testTypeName) {
            if ($this->hasTestType($testTypeName)) {
                $absoluteTestTypes[$testTypeKey] = 1;
            } else {
                $absoluteTestTypes[$testTypeKey] = 0;
            }
        }
        
        return $absoluteTestTypes;
    }
    
    
    /**
     * 
     * @return array
     */
    public function __toArray() {
        $optionsAsArray = array();
        
        if ($this->hasTestTypes()) {
            $optionsAsArray['test-types'] = $this->getTestTypes();
            
            foreach ($this->testTypes as $testTypeKey => $testType) {                                            
                if (!isset($optionsAsArray['test-type-options'])) {
                    $optionsAsArray['test-type-options'] = array();
                }
            }
            
            foreach ($this->availableTaskTypes as $taskTypeKey => $taskTypeName) { 
                $optionsAsArray['test-type-options'][$taskTypeName] = $this->getAbsoluteTestTypeOptions($taskTypeKey, false);
            }
        }
        
        return $optionsAsArray;   
    }

    
}