<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\ActionTest as BaseActionTest;
use SimplyTestable\WebClientBundle\Model\User;

class ActionTest extends BaseActionTest {
    
    protected function getActionName() {
        return 'indexAction';
    }
    
    protected function getTestMethodHasFixtureMap() {
        return array(
            'testWithAuthorisedUser' => true
        );
    }
    
    public function testWithUnauthorisedUser() {        
        $this->performActionTest(array(
            'statusCode' => 200
        ));
    }
    
    public function testWithAuthorisedUser() {     
        $this->setUser(new User('user@example.com','password'));
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));      
        
        $this->performActionTest(array(
            'statusCode' => 200
        ));  
    }
    
    public function testWithNonExistentUser() {
        $this->setUser(new User('user@example.com','password'));               
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));      
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signout/'
        ));          
    }

}


