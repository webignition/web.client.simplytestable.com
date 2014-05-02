<?php

namespace SimplyTestable\WebClientBundle\Tests\Event\MailChimp\Event\GetType;

use SimplyTestable\WebClientBundle\Tests\Event\MailChimp\Event\EventTest;

abstract class GetTypeTest extends EventTest {
    
    protected function getEventPostData() {
        return array(
            'type' => $this->getType()
        );
    }
    
    
    /**
     * 
     * @return string
     */
    protected function getType() {
        $classNameParts = explode('\\', get_class($this));        
        return strtolower(str_replace('Test', '', $classNameParts[count($classNameParts) - 1]));
    }
    
    
    public function testGetType() {
        $this->assertEquals($this->getType(), $this->event->getType());
    }

}