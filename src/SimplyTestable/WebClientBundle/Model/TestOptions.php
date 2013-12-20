<?php
namespace SimplyTestable\WebClientBundle\Model;

class TestOptions {      
    
    /**
     *
     * @var array
     */
    private $availableTaskTypes = array();    
    
    
    /**
     *
     * @var array
     */    
    private $availableFeatures = array();
    
    /**
     *
     * @var array
     */
    private $testTypes = array();    
    
    /**
     *
     * @var array
     */
    private $testTypeOptions = array();    
    
    /**
     *
     * @var array
     */
    private $features = array();
    
    /**
     * 
     * @param array $availableTaskTypes
     */
    public function setAvailableTaskTypes($availableTaskTypes) {
        $this->availableTaskTypes = $availableTaskTypes;
    }
    
    
    /**
     * 
     * @param array $availableFeatures
     */
    public function setAvailableFeatures($availableFeatures) {
        $this->availableFeatures = $availableFeatures;
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
     * @param string $testTypeKey
     */
    public function removeTestType($testTypeKey) {
        if (array_key_exists($testTypeKey, $this->testTypes)) {
            unset($this->testTypes[$testTypeKey]);
        }        
    }
    
    
    /**
     * 
     * @param string $featureKey
     * @param array $featureOptions
     */
    public function addFeatureOptions($featureKey, $featureOptions) {
        $this->features[$featureKey] = $featureOptions;
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
     * @return boolean
     */
    public function hasFeatures() {
        return count($this->features) > 0;
    }
    
    
    
    /**
     * 
     * @param string $testType
     * @return boolean
     */
    public function hasTestType($testType) {
        return in_array($testType, $this->testTypes);
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
     * @return array
     */
    public function getFeatures() {
        $features = array();
        
        foreach ($this->features as $featureKey => $featureOptions) {
            $features[] = $featureKey;
        }
        
        return $features;
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
     * @param string $feature
     * @return boolean
     */
    public function hasFeatureOptions($feature) {
        if (!isset($this->features[$feature])) {
            return false;
        }
        
        if (!is_array($this->features[$feature])) {
            return false;
        }
        
        return count($this->features[$feature]) > 0;
    }     
    
    
    
    /**
     * 
     * @param string $feature
     * @return array
     */
    public function getFeatureOptions($feature) {        
        if ($this->hasFeatureOptions($feature)) {            
            return $this->features[$feature];
        }
        
        return array();
    }
    
    
    public function removeFeatureOptions($feature) {
        if ($this->hasFeatureOptions($feature)) {            
            unset($this->features[$feature]);
        }
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
    
    
    public function getAbsoluteFeatureOptions($featureKey) {
        $absoluteFeatureOptions = array();
        $featureOptions = $this->getFeatureOptions($featureKey);
        
        foreach ($featureOptions as $optionKey => $optionValue) {
            //var_dump($optionKey, $optionValue);
            
//            $key = ($useFullOptionKey) ? $optionKey : str_replace($testType.'-', '', $optionKey); 
            $absoluteFeatureOptions[$optionKey] = $optionValue;
        }
        
        return $absoluteFeatureOptions;        
        
//        var_dump($featureKey, $featureOptions);
//        exit();
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
        
        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {
            if ($this->hasTestType($testTypeName)) {
                $absoluteTestTypes[$testTypeKey] = 1;
            } else {
                $absoluteTestTypes[$testTypeKey] = 0;
            }
        }
        
        return $absoluteTestTypes;
    }
    
    
    public function getAbsoluteFeatures() {
        $absoluteFeautures = array();
        
        foreach ($this->availableFeatures as $featureKey => $featureOptions) {            
            if ($this->hasFeatureOptions($featureKey)) {
                $absoluteFeautures[$featureKey] = 1;
            } else {
                $absoluteFeautures[$featureKey] = 0;
            }
        }
        
        return $absoluteFeautures;        
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
        
        if ($this->hasFeatures()) {
            if (!isset($optionsAsArray['parameters'])) {
                $optionsAsArray['parameters'] = array();
            }
            
            foreach ($this->getFeatures() as $featureKey) {
                $optionsAsArray['parameters'] = array_merge($optionsAsArray['parameters'], $this->getAbsoluteFeatureOptions($featureKey));
            }           
        }
        
        return $optionsAsArray;   
    }
    
    
    public function __toKeyArray() {
        $optionsAsArray = array();
        
        foreach ($this->features as $featureKey => $featureOptions) {
            $optionsAsArray[$featureKey] = 1;
            
            foreach ($featureOptions as $optionKey => $optionValue) {
                $optionsAsArray[$optionKey] = $optionValue;
            }
        }
        
        foreach ($this->testTypes as $testTypeKey => $testType) {
            $optionsAsArray[$testTypeKey] = 1;
        }
        
        foreach ($this->testTypeOptions as $testTypeKey => $testTypeOptions) {
            foreach ($testTypeOptions as $optionKey => $optionValue) {
                $optionsAsArray[$optionKey] = $optionValue;
            }
        }
        
        return $optionsAsArray;
    }

    
}