<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\QueuedAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {         
   
    protected function getActionName() {
        return 'queuedAction';
    }

    public function testWithAuthorisedUserWithQueuedTest() {
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->getTestQueueService()->clear();
        
        $this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            'http://example.com/',
            $testOptions,
            'full site',
            503
        );
        
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/'
            )
        ));
    }
    
    public function testWithUnauthorisedUserWithQueuedTest() {
        $notThePublicUser = new \SimplyTestable\WebClientBundle\Model\User();
        $notThePublicUser->setUsername('different-user');
        $notThePublicUser->setPassword('password');
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->getTestQueueService()->clear();
        
        $this->getTestQueueService()->enqueue(
            $notThePublicUser,
            'http://example.com/',
            $testOptions,
            'full site',
            503
        );
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'methodArguments' => array(
                'http://example.com/'
            )
        ));
    } 
    
    
    public function testWithAuthorisedUserWithoutQueuedTest() {
        $this->getTestQueueService()->clear();        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'            
        ), array(
            'methodArguments' => array(
                'http://example.com/'
            )
        ));   
    }
    
    public function testWithUnauthorisedUserWithoutQueuedTest() {
        $this->getTestQueueService()->clear();        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'            
        ), array(
            'methodArguments' => array(
                'http://example.com/'
            )
        ));  
    }
    

   
}


