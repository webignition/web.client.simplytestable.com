<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect\TestAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Redirect\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {   
    
    protected function getActionName() {
        return 'testAction';
    }    
    
    
    public function testWithWebSiteOnly() { 
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $website = 'http://example.com/';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/'
        ), array(
            'postData' => array(
                'website' => $website
            ),
            'methodArguments' => array(
                $website
            )
        ));
    }    

}


