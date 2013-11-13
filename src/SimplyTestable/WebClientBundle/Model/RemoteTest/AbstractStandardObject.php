<?php
namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

class AbstractStandardObject {
    
    
    /**
     *
     * @var \stdClass
     */
    private $source;
    
    
    /**
     * 
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source) {
        $this->source = $source;
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    public function getSource() {
        return $this->source;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getArraySource() {
        return json_decode(json_encode($this->getSource()), true);
    }
    
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    protected function getProperty($name) {
        if (!$this->hasProperty($name)) {
            return null;
        }
        
        return $this->getSource()->$name;
    }
    
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    protected function hasProperty($name) {
        return isset($this->getSource()->$name);
    }    
}