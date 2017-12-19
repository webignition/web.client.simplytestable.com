<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base;

abstract class ActionExceptionTest extends BaseTest {
    
    
    /**
     *
     * @var \Exception
     */
    protected $exception;
    
    public function setUp() {
        parent::setUp();

        if (count($this->getHttpFixtureItems()) > 0) {
            $this->setHttpFixtures($this->buildHttpFixtureSet($this->getHttpFixtureItems()));
        }
        
        $controller = $this->getCurrentController($this->getRequestPostData(), $this->getRequestQueryData());               

        $this->container->enterScope('request');

        try {
            $this->response = call_user_func_array(array($controller, $this->getActionName()), $this->getActionMethodArguments());
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }
    
    protected function getHttpFixtureItems() {
        return array();
    }

    protected function getRequestPostData() {
        return array();
    }

    protected function getRequestQueryData() {
        return array();
    }

    protected function getActionMethodArguments() {
        return array();
    }

    abstract protected function getExpectedExceptionClass();
    abstract protected function getExpectedExceptionCode();
    abstract protected function getExpectedExceptionMessage();

    public function testHasException() {
        $this->assertInstanceOf('\Exception', $this->exception);
    }


    public function testExceptionClass() {
        $this->assertInstanceOf($this->getExpectedExceptionClass(), $this->exception);
    }


    public function testExceptionCode() {
        $this->assertEquals($this->getExpectedExceptionCode(), $this->exception->getCode());
    }

    public function testExceptionMessage() {
        $this->assertEquals($this->getExpectedExceptionMessage(), $this->exception->getMessage());
    }





//
//    public function testResponseStatusCode() {
//        $this->assertEquals($this->getExpectedResponseStatusCode(), $this->response->getStatusCode());
//    }
//
//    protected function assertHasCookieNamed($name) {
//        $this->assertTrue($this->responseHasCookieNamed($name), 'Response does not contain cookie named "' . $name . '"');
//    }
//
//    protected function assertCookieValue($name, $value) {
//        $this->assertHasCookieNamed($name);
//        $this->assertEquals($value, $this->getResponseCookieValue($name));
//    }
//
//
//    /**
//     *
//     * @param string $name
//     * @return boolean
//     */
//    private function responseHasCookieNamed($name) {
//        foreach ($this->response->headers->getCookies() as $cookie) {
//            /* @var $cookie \Symfony\Component\HttpFoundation\Cookie */
//
//            if ($cookie->getName() == $name) {
//                return true;
//            }
//        }
//
//        return false;
//    }
//
//
//    /**
//     *
//     * @param string $name
//     * @return mixed
//     */
//    private function getResponseCookieValue($name) {
//        if (!$this->responseHasCookieNamed($name)) {
//            return null;
//        }
//
//        foreach ($this->response->headers->getCookies() as $cookie) {
//            /* @var $cookie \Symfony\Component\HttpFoundation\Cookie */
//
//            if ($cookie->getName() == $name) {
//                return $cookie->getValue();
//            }
//        }
//    }
    
    
    
    
    


}
