<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    protected function getControllerName() {
        return self::APP_CONTROLLER_NAME;
    }    
    
}
