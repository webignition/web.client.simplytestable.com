<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect\TestAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Redirect\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {   
    
    protected function getActionName() {
        return 'testAction';
    }    
    
    public function testWithWebSiteOnlyThatIsQueued() {
        $this->getTestQueueService()->clear();
        $website = 'http://example.com/';
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');        
        $this->assertTrue($this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            $website,
            $testOptions,
            'full site',
            503
        ));        
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//queued/'
        ), array(
            'postData' => array(
                'website' => $website
            ),
            'methodArguments' => array(
                $website
            )
        ));
    }
    
    public function testWithWebSiteOnly() { 
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $this->getTestQueueService()->clear();
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


