<?php
namespace SimplyTestable\WebClientBundle\Model;

abstract class Object {    
    
    /**
     *
     * @var \stdClass
     */
    private $data;
    
    
    /**
     * 
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data) {
        $this->data = $data;
    }   
    
    
    /**
     * 
     * @return \stdClass
     */
    protected function getData() {
        return $this->data;
    }
    
    
    /**
     * 
     * @param string $key
     * @return mixed
     */
    protected function getDataProperty($key) {
        return (isset($this->data->{$key})) ? $this->data->{$key} : null;
    }
    
    
    /**
     * 
     * @param string $key
     * @param mixed $value
     */
    protected function setDataProperty($key, $value) {
        $this->data->{$key} = $value;
    }
    
    
    /**
     * 
     * @param string $key
     * @return boolean
     */
    protected function hasDataProperty($key) {
        return property_exists($this->data, $key);
    }
    
    
    
}