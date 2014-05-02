<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\ResetPassword\Choose\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {        
    
    protected function getHttpFixtureItems() {
        return array(
            "HTTP/1.0 200 OK\nContent-Type:application/json\n\n\"foo\""
        );
    }
  
    protected function getExpectedResponseStatusCode() {
        return 200;
    }
    
    
    protected function getActionMethodArguments() {
        return array(
            'email' => 'user@example.com',
            'token' => 'foo'
        );
    }
}


 