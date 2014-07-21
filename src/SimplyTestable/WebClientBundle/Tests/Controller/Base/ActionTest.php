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



    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Service
     */
    protected function getMailService() {
        return $this->container->get('simplytestable.services.mail.service');
    }


    protected function assertLastMailMessageTextContains($value) {
        $this->assertLastMailMessagePropertyContains('textMessage', $value);
    }


    protected function assertLastMailMessageSubjectContains($value) {
        $this->assertLastMailMessagePropertyContains('subject', $value);
    }


    private function assertLastMailMessagePropertyContains($property, $value) {
        $this->assertTrue(
            substr_count($this->getLastMailMessageProperty($property), $value) > 0,
            'Last mail message ' . $property . ' does not contain "'.$value.'"'
        );
    }


    /**
     * @param string $property
     * @return string
     */
    private function getLastMailMessageProperty($property) {
        $lastMessage = $this->getLastMailMessage();
        $refObject = new \ReflectionObject($lastMessage);
        $refProperty = $refObject->getProperty($property);
        $refProperty->setAccessible(true);

        return $refProperty->getValue($lastMessage);
    }


    /**
     * @return \MZ\PostmarkBundle\Postmark\Message
     */
    private function getLastMailMessage() {
        return $this->getMailService()->getSender()->getLastMessage();
    }


    protected function assertHasFlashValue($key) {
        $value = $this->container->get('session')->getFlashBag()->peek($key);

        $this->assertTrue(is_array($value), "Flash key '$key' is not set");
        $this->assertTrue(count($value) > 0,  "Flash key '$key' is empty");
    }


    protected function assertFlashValueIs($key, $value) {
        $this->assertEquals($value, $this->container->get('session')->getFlashBag()->peek($key));
    }

}
