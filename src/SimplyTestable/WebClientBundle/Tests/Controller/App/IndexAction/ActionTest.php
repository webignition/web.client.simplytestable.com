<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\ActionTest as BaseActionTest;
use SimplyTestable\WebClientBundle\Model\User;

class ActionTest extends BaseActionTest {
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }    
    
    protected function getActionName() {
        return 'indexAction';
    }   
    
    public function testWithAuthorisedUser() {     
        $this->setUser(new User('user@example.com','password'));   
        
        $this->performActionTest(array(
            'statusCode' => 200
        ));  
    }
    
    public function testWithNonExistentUser() {
        $this->setUser(new User('user@example.com','password'));                  
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signout/'
        ));          
    }

}


