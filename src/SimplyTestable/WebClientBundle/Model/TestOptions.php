<?php
namespace SimplyTestable\WebClientBundle\Model;

class TestOptions {      
    
    private $testTypes = array();
    
    
    private $testTypeMap = array(
        'html-validation' => 'HTML validation',
        'css-validation' => 'CSS validation'
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