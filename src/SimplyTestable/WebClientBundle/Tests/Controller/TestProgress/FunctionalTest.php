<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestProgress;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    
    protected function getActionName() {
        return 'indexAction';
    }

    protected function getRoute() {
        return 'app_progress';
    }    
    
    protected function getControllerName() {
        return self::TEST_PROGRESS_CONTROLLER_NAME;
    }    
    
}
