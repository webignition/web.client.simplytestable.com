<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\IndexAction;

use SimplyTestable\WebClientBundle\Tests\HttpTest as BaseHttpTest;

class HttpTest extends BaseHttpTest {  
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }  
    
    public function getControllerActionName() {
        return 'indexAction';
    }
    
    public function testWithAuthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 200
        ));
    }
    
    public function testWithAuthorisedUserWhereTasksNeedToBeRetrieved() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/results/preparing/'
        ));
    }    
    
    public function testWithUnauthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));
    } 
    
    
    public function testWithNonExistentTest() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));
    }   
    
    
    public function testWithUnfinishedTest() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ));
    } 
   
    
    
    private function performActionTest($responseProperties, $methodProperties = array()) {
        $controllerActionName = $this->getControllerActionName();
        
        list(, $caller) = debug_backtrace(false);
        
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($caller['function'] . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $postData = isset($methodProperties['postData']) ? $methodProperties['postData'] : array();
        $queryData = isset($methodProperties['queryData']) ? $methodProperties['queryData'] : array();
        
        $response = $this->getTestResultsController(
            $controllerActionName,
            $postData,
            $queryData
        )->$controllerActionName('http://example.com/', 1);
        
        $this->assertEquals($responseProperties['statusCode'], $response->getStatusCode());        
        
        if ($response->getStatusCode() == 302) {
            $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
            $this->assertEquals($responseProperties['redirectPath'], $redirectUrl->getPath());            
        }
    }
    
    
//    /**
//     * Get the name of the controller action method from the test class name
//     * 
//     * @return string
//     */
//    private function getControllerActionName() {
//        $classNameParts = explode('\\', __CLASS__);        
//        return str_replace('HttpTest', '', $classNameParts[count($classNameParts) - 1]);
//    }
    
    
    public function testWithHttpClientErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        try {
            $this->getTestResultsController('indexAction')->indexAction('http://example.com/', 1);
            $this->fail('WebResourceException 404 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(400, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }    
    
    public function testWithHttpServerErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        try {
            $this->getTestResultsController('indexAction')->indexAction('http://example.com/', 1);
            $this->fail('WebResourceException 500 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(500, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }
    
    
    public function testWithCurlErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http%3A%2F%2Fexample.com%2F/1/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));
        
        $this->container->enterScope('request');
        
        try {
            $this->getTestResultsController('indexAction')->indexAction('http://example.com/', 1);
            $this->fail('CurlException 6 has not been raised.');
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->assertEquals(6, $curlException->getErrorNo());
            return;
        };
    }     
    

   
}


