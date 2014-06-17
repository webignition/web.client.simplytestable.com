<?php
namespace SimplyTestable\WebClientBundle\Services\TestOptions\Adapter;

use SimplyTestable\WebClientBundle\Model\TestOptions;
use Symfony\Component\HttpFoundation\ParameterBag;

class Request {
    
    /**
     *
     * @var ParameterBag
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
    private $availableFeatures = array();
    
    
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
     * @param ParameterBag $requestData
     */
    public function setRequestData(ParameterBag $requestData) {
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
     * @param array $featuresDefinition
     */
    public function setAvailableFeatures($featuresDefinition) {
        $this->availableFeatures = $featuresDefinition;
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
        $this->testOptions->setAvailableFeatures($this->availableFeatures);
        
        $features = $this->parseFeatures();
        foreach ($features as $featureKey => $featureOptions) {
            $this->testOptions->addFeatureOptions($featureKey, $this->parseFeatureOptions($this->getFormKeyFromFeatureKey($featureKey)));
        }  
        
        $testTypes = $this->parseTestTypes();

        foreach ($testTypes as $testTypeKey => $testTypeName) {
            $this->testOptions->addTestType($testTypeKey, $testTypeName);
        }        
        
        foreach ($this->availableTaskTypes as $testTypeKey => $testTypeName) {            
            $this->testOptions->addTestTypeOptions($testTypeKey, $this->parseTestTypeOptions($testTypeKey));
        }
    } 
    
    private function getFormKeyFromFeatureKey($featureKey) {
        if (!isset($this->availableFeatures[$featureKey])) {
            return null;
        }
        
        return $this->availableFeatures[$featureKey]['form_key'];
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
     * @return array
     */
    private function parseFeatures() {
        $features = array();
        
        foreach ($this->availableFeatures as $featureKey => $featureOptions) {            
            if (isset($featureOptions['enabled']) && $featureOptions['enabled'] === true) {
                $features[$featureKey] = $featureOptions;
            }
        }
        
        return $features;
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
    
    
    private function parseFeatureOptions($featureFormKey) {        
        $featureOptions = array();
       
        // only handles parsing out feature options when at least something (the parent key) is present in the request data
        // how to handle default feature options that are missing entirely from the request data?
        
        foreach ($this->requestData as $key => $value) {            
            //var_dump($key, $featureFormKey, $this->requestKeyMatchesFeatureKey($key, $featureFormKey), array_key_exists($key, $this->namesAndDefaultValues));
            
            if ($this->requestKeyMatchesFeatureKey($key, $featureFormKey) && array_key_exists($key, $this->namesAndDefaultValues)) {               
                
                switch (gettype($this->namesAndDefaultValues[$key])) {
                    case 'integer':
                        $featureOptions[$key] = (int)$value;
                        break;
                    
                    case 'array':
                        $rawValues = (is_string($value)) ? explode("\n", $value) : $value;                        
                        $featureOptions[$key] = $this->cleanRawValues($rawValues);
                        break;
                        
                    default:
                        $featureOptions[$key] = $value;
                        break;
                }
            }
        }
        
        return $featureOptions;
    }
    
    
    private function cleanRawValues($rawValues) {
        $cleanedValues = array();
        
        foreach ($rawValues as $key => $rawValue) {
            if (is_string($rawValue)) {
                $value = trim($rawValue);
                $cleanedValues[$key] = ($value == '') ? null : $value;
            } elseif (is_array($rawValue)) {
                $cleanedValues[$key] = $this->cleanRawValues($rawValue);
            } else {
                $cleanedValues[$key] = $rawValue;
            }
        }
        
        return $cleanedValues;
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
    
    
    /**
     * 
     * @param string $requestKey
     * @param string $featureFormKey
     * @return boolean
     */
    private function requestKeyMatchesFeatureKey($requestKey, $featureFormKey) {        
        return substr($requestKey, 0, strlen($featureFormKey)) == $featureFormKey;
    }
        
}