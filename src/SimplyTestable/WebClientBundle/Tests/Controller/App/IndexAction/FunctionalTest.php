<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {
    
    protected function getActionName() {
        return 'indexAction';
    }    
    
    protected function getRoute() {
        return 'app';
    }    
    
    protected function getControllerName() {        
        return self::APP_CONTROLLER_NAME;
    }    
    
}
