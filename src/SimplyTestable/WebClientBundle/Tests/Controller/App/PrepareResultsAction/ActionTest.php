<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\PrepareResultsAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }        
    
    protected function getActionName() {
        return 'prepareResultsAction';
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

    public function testWithUnauthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }


    public function testWithPublicTestAccessedByNonOwner() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }


    public function testWithNonExistentTest() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }


    public function testWithUnfinishedTest() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }


    public function testWithAuthorisedUserAsJson() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'queryData' => array(
                'output' => 'json'
            ),
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }
    
    public function testWithHttpClientErrorRetrievingRemoteSummary() {                
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1
                )            
            )); 
            $this->fail('WebResourceException 400 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(400, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }    
    
    public function testWithHttpServerErrorRetrievingRemoteSummary() {
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1
                )            
            ));
            $this->fail('WebResourceException 500 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(500, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }
    
    
    public function testWithCurlErrorRetrievingRemoteSummary() {
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1
                )            
            ));            
            $this->fail('CurlException 6 has not been raised.');
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->assertEquals(6, $curlException->getErrorNo());
            return;
        };
    }

   
}


