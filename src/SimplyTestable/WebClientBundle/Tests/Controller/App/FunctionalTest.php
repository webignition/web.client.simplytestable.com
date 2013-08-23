<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    protected function getCurrentRequestUrl($parameters = null) {
        $parameters = (is_array($parameters)) ? $parameters : array();
        
        return $this->getCurrentController()->generateUrl($this->getRoute(), $parameters);
    }
    
    protected function getControllerName() {
        return self::APP_CONTROLLER_NAME;
    }    
    
}
