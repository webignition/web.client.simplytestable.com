<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\RejectedAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestResults\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {       
    
    const WEBSITE = 'http://abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz/';
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }     

    protected function getActionName() {
        return 'rejectedAction';
    }
    
    /**
     * test without plan limit reached
     * test with plan limit reached
     * test with unroutable
     */
    
    
    public function testWithAuthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                self::WEBSITE,
                1                
            )
        ));
    }
  
    

   
}


