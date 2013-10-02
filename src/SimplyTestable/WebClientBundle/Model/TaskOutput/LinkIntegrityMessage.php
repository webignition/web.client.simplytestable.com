<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class LinkIntegrityMessage extends Message {
       
    /**
     *
     * @var string
     */
    private $context;
    
    /**
     *
     * @var string
     */
    private $url;
    
    
    /**
     * 
     * @param string $context
     */
    public function setContext($context) {
        $this->context = $context;
    }
    
    /**
     * 
     * @return string
     */
    public function getContext() {
        return $this->context;
    }
    
    
    /**
     * 
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }
}