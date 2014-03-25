<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {   
    
    protected function getControllerName() {
        return self::TEST_RESULTS_CONTROLLER_NAME;
    }    
    
}
