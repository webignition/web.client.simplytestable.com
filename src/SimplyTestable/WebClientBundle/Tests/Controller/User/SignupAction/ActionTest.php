<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\SignupAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    protected function getActionName() {
        return 'signupAction';
    }    
    
    public function testPostSignupRedirectParametersAreStoredInCookie() {        
        $this->performActionTest(array(
            'statusCode' => 200,
            'cookies' => array(
                'simplytestable-redirect' => array(
                    'value' => 'foo'
                )
            )
        ), array(
            'queryData' => array(
                'redirect' => 'foo'
            )
        ));
    }
    

}


 