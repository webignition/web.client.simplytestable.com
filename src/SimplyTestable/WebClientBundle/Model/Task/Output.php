<?php

namespace SimplyTestable\WebClientBundle\Model\Task;

use webignition\InternetMediaType\InternetMediaType;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;


class Output {
    
    
    /**
     *
     * @var string
     * @SerializerAnnotation\Accessor(getter="getPublicSerializedContent")
     */
    private $content;

    
    
    /**
     *
     * @param string $content
     * @return \SimplyTestable\WebClientBundle\Model\Task\Output 
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
    
    
    /**
     *
     * @return string 
     */
    public function getContent() {
        return $this->content;
    }
    
    
    /**
     * @return string
     */
    public function getPublicSerializedContent() {
        return $this->getContent();
    }    
    
}