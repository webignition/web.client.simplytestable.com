<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer;

class FactoryService {
    
    /**
     * Collection of TaskOutputDeserializer objects
     * 
     * @var array 
     */
    private $deserializers = array();
    
    
    /**
     *
     * @param array $deserializerConfiguration 
     */
    public function __construct($deserializerConfiguration) {
        foreach ($deserializerConfiguration as $taskType => $deserializerGroup) {
            if (!isset($this->deserializers[$taskType])) {
                $this->deserializers[$taskType] = array();
            }
            
            foreach ($deserializerGroup as $contentType => $deserializerDetail) {                
                $this->deserializers[$taskType][$contentType] = new $deserializerDetail['class'];
            }
        }
    }
    
    
    /**
     *
     * @param string $taskType
     * @param string $contentType
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer\TaskOutputDeserializer 
     */
    public function getDeserializer($taskType, $contentType) {
        if (!isset($this->deserializers[$taskType])) {
            return false;
        }
        
        if (!isset($this->deserializers[$taskType][$contentType])) {
            return false;
        }
        
        return $this->deserializers[$taskType][$contentType];
    }
    
}