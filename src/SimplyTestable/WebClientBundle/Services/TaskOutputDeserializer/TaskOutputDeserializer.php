<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer;

use SimplyTestable\WebClientBundle\Entity\Task\Output;

abstract class TaskOutputDeserializer {    
    
    /**
     * @param string $output
     * @return Output
     */
    abstract public function deserialize($output);
    
    
}