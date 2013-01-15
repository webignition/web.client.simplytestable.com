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
            'css-validation-ignore-warnings' => array(
                'type' => 'int',
                'default' => 0
            ),
            'css-validation-ignore-common-cdns' => array(
                'type' => 'int',
                'default' => 0                
            ),
            'css-validation-vendor-extensions' => array(
                'type' => 'string',
                'default' => 'warn'               
            ),
            'css-validation-domains-to-ignore' => array(
                'type' => 'array',
                'default' => array()              
            ),
        ),
        'js-static-analysis' => array(
            'js-static-analysis-ignore-common-cdns' => array(
                'type' => 'int',
                'default' => 0                
            ),            
            'js-static-analysis-domains-to-ignore' => array(
                'type' => 'array',
                'default' => array()              
            ),  
            'js-static-analysis-jslint-option-passfail' => array(    
                'type' => 'int',
                'default' => 0
            ),
            'js-static-analysis-jslint-option-bitwise' => array(    
                'type' => 'int',
                'default' => 0
            ),            
            'js-static-analysis-jslint-option-continue' => array(    
                'type' => 'int',
                'default' => 0
            ),               
            'js-static-analysis-jslint-option-debug' => array(    
                'type' => 'int',
                'default' => 0
            ), 
            'js-static-analysis-jslint-option-evil' => array(    
                'type' => 'int',
                'default' => 0
            ),             
            'js-static-analysis-jslint-option-eqeq' => array(    
                'type' => 'int',
                'default' => 0
            ), 
            'js-static-analysis-jslint-option-es5' => array(    
                'type' => 'int',
                'default' => 0
            ), 
            'js-static-analysis-jslint-option-forin' => array(    
                'type' => 'int',
                'default' => 0
            ), 
            'js-static-analysis-jslint-option-newcap' => array(    
                'type' => 'int',
                'default' => 0
            ),
            'js-static-analysis-jslint-option-nomen' => array(    
                'type' => 'int',
                'default' => 0
            ),            
            'js-static-analysis-jslint-option-plusplus' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-regexp' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-undef' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-unparam' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-sloppy' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-stupid' => array(    
                'type' => 'int',
                'default' => 0
            ),   
            'js-static-analysis-jslint-option-sub' => array(    
                'type' => 'int',
                'default' => 0
            ), 
            
            'js-static-analysis-jslint-option-vars' => array(    
                'type' => 'int',
                'default' => 0
            ),  
            'js-static-analysis-jslint-option-white' => array(    
                'type' => 'int',
                'default' => 0
            ),  
            'js-static-analysis-jslint-option-anon' => array(    
                'type' => 'int',
                'default' => 0
            ),
            'js-static-analysis-jslint-option-browser' => array(    
                'type' => 'int',
                'default' => 0
            ),
            'js-static-analysis-jslint-option-devel' => array(    
                'type' => 'int',
                'default' => 0
            ),                        
            'js-static-analysis-jslint-option-windows' => array(    
                'type' => 'int',
                'default' => 0
            ),               
            'js-static-analysis-jslint-option-maxerr' => array(    
                'type' => 'int',
                'default' => 50
            ),     
            'js-static-analysis-jslint-option-indent' => array(    
                'type' => 'int',
                'default' => 4
            ),             
            'js-static-analysis-jslint-option-maxlen' => array(    
                'type' => 'int',
                'default' => 256
            ),  
            'js-static-analysis-jslint-option-predef' => array(
                'type' => 'array',
                'default' => array()              
            ),              
        ),
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
        
        foreach ($this->testTypeOptionsMap[$testType] as $optionKey => $optionDefinition) {
            $key = ($useFullOptionKey) ? $optionKey : str_replace($testType.'-', '', $optionKey);            
            
            if (isset($testTypeOptions[$optionKey])) {
                switch ($optionDefinition['type']) {
                    case 'array':
                        $optionValue = explode("\n", $testTypeOptions[$optionKey]);
                        foreach ($optionValue as $index => $value) {
                            $optionValue[$index] = trim($value);
                        }
                        break;
                        
                    case 'int':
                        $optionValue = (int)$testTypeOptions[$optionKey];
                        break;

                    default:
                        $optionValue = $testTypeOptions[$optionKey];
                        break;
                }                
            } else {
                $optionValue = $optionDefinition['default'];
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