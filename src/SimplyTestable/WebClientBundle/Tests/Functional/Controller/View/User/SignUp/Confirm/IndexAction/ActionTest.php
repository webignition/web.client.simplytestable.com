<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\SignUp\Confirm\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {        
    
    protected function getHttpFixtureItems() {
        return array(
            "HTTP/1.0 200 OK",
            "HTTP/1.0 200 OK"
        );
    }    
  
    protected function getExpectedResponseStatusCode() {
        return 200;
    }
    
    
    protected function getRequestQueryData() {
        return array(
            'email' => 'user@example.com'
        );
    }
    

    protected function getActionMethodArguments() {
        return array(
            'email' => 'user@example.com'
        );
    }


    protected function getRequestAttributes() {
        return array(
            'email' => 'user@example.com'
        );
    }
        
}


 