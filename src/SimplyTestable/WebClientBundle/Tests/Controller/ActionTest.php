<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller;

use SimplyTestable\WebClientBundle\Tests\Controller\BaseTest;

abstract class ActionTest extends BaseTest {
    
    
    /**
     * 
     * @param array $responseProperties
     * @param array $methodProperties
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function performActionTest($responseProperties, $methodProperties = array()) {
        $actionName = $this->getActionName();
        $postData = isset($methodProperties['postData']) ? $methodProperties['postData'] : array();
        $queryData = isset($methodProperties['queryData']) ? $methodProperties['queryData'] : array();             
        $methodArguments = isset($methodProperties['methodArguments']) ? $methodProperties['methodArguments'] : array();
        
        $this->container->enterScope('request');
        
        $controller = $this->getCurrentController($postData, $queryData);
        
        $response = call_user_func_array(array($controller, $actionName), $methodArguments);
        
        $this->assertEquals($responseProperties['statusCode'], $response->getStatusCode());        
        
        if ($response->getStatusCode() == 302) {
            $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
            $this->assertEquals($responseProperties['redirectPath'], $redirectUrl->getPath());            
        }
        
        return $response;
    }     

}
