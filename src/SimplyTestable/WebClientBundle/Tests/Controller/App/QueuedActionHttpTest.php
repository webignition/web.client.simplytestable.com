<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class QueuedActionHttpTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
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
        ));
    } 
    
    
    public function testWithAuthorisedUserWithoutQueuedTest() {
        $this->getTestQueueService()->clear();        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'            
        ));    
    }
    
    public function testWithUnauthorisedUserWithoutQueuedTest() {
        $this->getTestQueueService()->clear();        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'            
        ));    
    }    
    
    private function performActionTest($responseProperties, $methodProperties = array()) {
        $controllerActionName = $this->getControllerActionName();
        
        $this->removeAllTests();
        
        $this->container->enterScope('request');
        
        $postData = isset($methodProperties['postData']) ? $methodProperties['postData'] : array();
        $queryData = isset($methodProperties['queryData']) ? $methodProperties['queryData'] : array();
        
        $response = $this->getAppController(
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
    
    
    /**
     * Get the name of the controller action method from the test class name
     * 
     * @return string
     */
    private function getControllerActionName() {
        $classNameParts = explode('\\', __CLASS__);        
        return str_replace('HttpTest', '', $classNameParts[count($classNameParts) - 1]);
    }    
    

   
}


