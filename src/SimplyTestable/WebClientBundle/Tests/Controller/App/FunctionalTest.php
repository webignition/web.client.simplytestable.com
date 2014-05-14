<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    protected function getActionName() {
        return 'prepareResultsAction';
    }

    protected function getRoute() {
        return 'view_test_results_preparing_index_index';
    }    
    
    protected function getControllerName() {
        return self::APP_CONTROLLER_NAME;
    }    
    
}
