<?php
namespace SimplyTestable\WebClientBundle\Model;

class TestOptions {      
    
    private $testTypes = array();
    
    private $testTypeOptions = array();
    
    
    private $testTypeMap = array(
        'html-validation' => 'HTML validation',
        'css-validation' => 'CSS validation',
        'js-static-analysis' => 'JS static analysis'
    );
    
    private $testTypeOptionsMap = array(
        'html-validation' => array(),
        'css-validation' => array(
            'css-validation-ignore-warnings' => 'string',
            'css-validation-ignore-common-cdns' => 'string',
            'css-validation-vendor-extensions' => 'string',
            'css-validation-domains-to-ignore' => 'array'
        ),
        'js-static-analysis' => array(),
    );
    
    
    /**
     * 
     * @param string $testType
     */
    public function addTestType($testType) {
        if (!$this->hasTestType($testType)) {
            $this->testTypes[] = $testType;
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
     * @param string $testType
     * @return boolean
     */
    public function hasTestType($testType) {
        return in_array($testType, $this->testTypes);
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
        return $this->testTypes;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getTestTypeKeys() {
        $testTypeKeys = array();
        $testTypes = $this->getTestTypes();
        
        foreach ($testTypes as $testTypeName) {
            foreach ($this->testTypeMap as $testTypeKey => $testTypeNameValue) {
                if ($testTypeName == $testTypeNameValue) {
                    $testTypeKeys[] = $testTypeKey;
                }
            }
        }
        
        return $testTypeKeys;
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
        
        foreach ($this->testTypeOptionsMap[$testType] as $optionKey => $optionType) {
            $key = ($useFullOptionKey) ? $optionKey : str_replace($testType.'-', '', $optionKey);
            
            $optionValue = 0;
            if (isset($testTypeOptions[$optionKey])) {
                switch ($optionType) {
                    case 'array':
                        $optionValue = explode("\n", $testTypeOptions[$optionKey]);
                        foreach ($optionValue as $index => $value) {
                            $optionValue[$index] = trim($value);
                        }
                        break;

                    default:
                        $optionValue = $testTypeOptions[$optionKey];
                        break;
                }                
            }
            
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

    
}