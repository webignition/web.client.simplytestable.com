<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\FailedNoUrlsDetectedAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestResults\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {       
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }     

    protected function getActionName() {
        return 'failedNoUrlsDetectedAction';
    }
    
    
    public function testWithAuthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1                
            )
        ));
    }
  
    

   
}


