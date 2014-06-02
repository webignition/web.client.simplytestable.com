<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;

abstract class ActionTest extends BaseTest {

    private $expectedControllerExceptionClass = null;
    private $expectedControllerExceptionCode = null;
    private $expectedControllerExceptionMessage = null;
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;


    /**
     * @var \Exception
     */
    protected $controllerException;
    
    public function setUp() {
        parent::setUp();
        $this->removeAllTests();

        if (count($this->getHttpFixtureItems()) > 0) {
            $this->setHttpFixtures($this->buildHttpFixtureSet($this->getHttpFixtureItems()));
        }

        $controller = $this->getCurrentController($this->getRequestPostData(), $this->getRequestQueryData());

        $this->container->enterScope('request');

        $this->preCall();

        try {
            $this->response = call_user_func_array(array($controller, $this->getActionName()), $this->getActionMethodArguments());
        } catch (\Exception $e) {
            if ($this->hasExpectedException()) {
                $this->controllerException = $e;

                $this->assertInstanceOf($this->expectedControllerExceptionClass, $e);

                if (!is_null($this->expectedControllerExceptionCode)) {
                    $this->assertEquals($this->expectedControllerExceptionCode, $e->getCode());
                }


                if (!is_null($this->expectedControllerExceptionMessage)) {
                    $this->assertEquals($this->expectedControllerExceptionMessage, $e->getMessage());
                }

            } else {
                throw $e;
            }
        }
    }


    /**
     * @return bool
     */
    private function hasExpectedException() {
        return !is_null($this->expectedControllerExceptionClass);
    }


    protected function setExpectedControllerExceptionClass($class) {
        $this->expectedControllerExceptionClass = $class;
    }


    protected function setExpectedControllerExceptionCode($code) {
        $this->expectedControllerExceptionCode = $code;
    }


    protected function setExpectedControllerExceptionMessage($message) {
        $this->expectedControllerExceptionMessage = $message;
    }


    protected function preCall() {
    }
    
    
    protected function getHttpFixtureItems() {
        return array();
    }
    
    
    abstract protected function getExpectedResponseStatusCode();

    protected function getRequestPostData() {
        return array();
    }
    
    protected function getRequestQueryData() {
        return array();
    }
    
    protected function getActionMethodArguments() {
        return array();
    }
    
    public function testResponseStatusCode() {
        if ($this->response) {
            $this->assertEquals($this->getExpectedResponseStatusCode(), $this->response->getStatusCode());
        }
    }
    
    protected function assertHasCookieNamed($name) {
        $this->assertTrue($this->responseHasCookieNamed($name), 'Response does not contain cookie named "' . $name . '"');
    }    
    
    protected function assertCookieValue($name, $value) {
        $this->assertHasCookieNamed($name);
        $this->assertEquals($value, $this->getResponseCookieValue($name));
    }

    protected function assertResponseLocationHeader($expectedLocation) {
        $this->assertResponseHasLocationHeader();
        $this->assertEquals($expectedLocation, $this->response->headers->get('location'), 'Resp');
    }


    protected function assertResponseHasLocationHeader() {
        $this->assertTrue($this->response->headers->has('location'), 'Response has no "location" header');
    }


    
    
    /**
     * 
     * @param string $name
     * @return boolean
     */
    private function responseHasCookieNamed($name) {
        foreach ($this->response->headers->getCookies() as $cookie) {
            /* @var $cookie \Symfony\Component\HttpFoundation\Cookie */
            
            if ($cookie->getName() == $name) {
                return true;
            }
        }
        
        return false;
    }
    
    
    /**
     * 
     * @param string $name
     * @return mixed
     */
    private function getResponseCookieValue($name) {
        if (!$this->responseHasCookieNamed($name)) {
            return null;
        }
        
        foreach ($this->response->headers->getCookies() as $cookie) {
            /* @var $cookie \Symfony\Component\HttpFoundation\Cookie */
            
            if ($cookie->getName() == $name) {
                return $cookie->getValue();
            }
        }      
    }
    
    
    
    
    
//    /**
//     * 
//     * @param array $responseProperties
//     * @param array $methodProperties
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    protected function performActionTest($responseProperties, $methodProperties = array()) {
//        $actionName = $this->getActionName();
//        $postData = isset($methodProperties['postData']) ? $methodProperties['postData'] : array();
//        $queryData = isset($methodProperties['queryData']) ? $methodProperties['queryData'] : array();             
//        $methodArguments = isset($methodProperties['methodArguments']) ? $methodProperties['methodArguments'] : array();
//        
//        $this->container->enterScope('request');
//        
//        $controller = $this->getCurrentController($postData, $queryData);
//        
//        /* @var $response \Symfony\Component\HttpFoundation\Response */
//        $response = call_user_func_array(array($controller, $actionName), $methodArguments);
//        
//        $this->assertEquals($responseProperties['statusCode'], $response->getStatusCode());        
//        
//        if ($response->getStatusCode() == 302) {
//            $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
//            $this->assertEquals($responseProperties['redirectPath'], $redirectUrl->getPath());            
//        }
//        
//        if (isset($responseProperties['flash'])) {
//            foreach ($responseProperties['flash'] as $key => $expectedValue) {
//                $this->assertEquals($expectedValue, $this->container->get('session')->getFlash($key));
//            }
//        }
//        
//        if (isset($responseProperties['cookies'])) {
//            foreach ($responseProperties['cookies'] as $name => $properties) {
//                $this->performCookieTest($name, $properties, $response);
//            }
//        }
//        
//        return $response;
//    }
//    
//    private function performCookieTest($name, $properties, $response) {
//        $cookieIsPresent = false;
//        
//        foreach ($response->headers->getCookies() as $cookie) {
//            if ($cookie->getName() == $name) {
//                $cookieIsPresent = true;
//                
//                if (isset($properties['value'])) {
//                    $this->assertEquals($properties['value'], $cookie->getValue());
//                }                  
//            }
//                                 
//        }        
//        
//        if ($cookieIsPresent === false) {
//            $this->fail('Cookie "'.$name.'" not present');
//        }
//    }

}
