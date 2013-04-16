<?php

namespace SimplyTestable\WebClientBundle\Tests;

abstract class BaseSimplyTestableTestCase extends BaseTestCase {
    
    const APP_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\AppController';    
    const TEST_START_CONTROLLER_NAME = 'SimplyTestable\WebClientBundle\Controller\TestStartController';    

    
    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\AppController
     */
    protected function getAppController($methodName, $postData = array()) {
        return $this->getController(self::APP_CONTROLLER_NAME, $methodName, $postData);
    }
    

    /**
     *
     * @param string $methodName
     * @param array $postData
     * @return \SimplyTestable\WebClientBundle\Controller\TestStartController
     */
    protected function getTestStartController($methodName, $postData = array()) {
        return $this->getController(self::TEST_START_CONTROLLER_NAME, $methodName, $postData);
    }    
   
    /**
     * 
     * @param string $controllerName
     * @param string $methodName
     * @return Symfony\Bundle\FrameworkBundle\Controller\Controller
     */
    private function getController($controllerName, $methodName, array $postData = array(), array $queryData = array()) {        
        return $this->createController($controllerName, $methodName, $postData, $queryData);
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\HttpClientService
     */
    protected function getHttpClientService() {
        return $this->container->get('simplytestable.services.httpclientservice');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestWebResourceService
     */    
    protected function getWebResourceService() {
        return $this->container->get('simplytestable.services.webresourceservice');
    }


}
