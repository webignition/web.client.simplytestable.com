<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\PrepareResultsAction;

use SimplyTestable\WebClientBundle\Tests\Controller\App\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    protected function getActionName() {
        return 'prepareResultsAction';
    }        
    
    public function testWithAuthorisedUser() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
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
    
    
    public function testWithNonExistentTest() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1
                )            
            )); 
            $this->fail('WebResourceException 404 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(400, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }    
    
    public function testWithHttpServerErrorRetrievingRemoteSummary() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http%3A%2F%2Fexample.com%2F/1/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));
        
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


