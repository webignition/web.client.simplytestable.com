<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    

    protected function getActionName() {
        return 'resultsAction';
    }

    public function testWithAuthorisedUserWithValidTestAndValidTask() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1,
                1
            )
        ));
    } 
    
    public function testWithPublicTestAccessedByNonOwner() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1,
                1
            )
        ));
    }       
    
    
    public function testWithAuthorisedUserWithValidTestAndInvalidTask() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1,
                999
            )
        ));
    }      
    
    public function testWithUnauthorisedUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(
            'methodArguments' => array(
                'http://techmites.com/',
                14636,
                8144097
            )
        ));
    }

}


