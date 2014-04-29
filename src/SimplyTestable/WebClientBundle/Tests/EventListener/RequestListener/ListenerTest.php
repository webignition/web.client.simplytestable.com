<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

abstract class ListenerTest extends BaseTestCase {
    
    const REQUEST_CONTROLLER = 'SimplyTestable\WebClientBundle\Controller\User\ViewController::signupAction';
    
    protected $event;
    
    public function setUp() {
        parent::setUp();
        $this->callListener();
    }
    
    protected function getListenerMethodName() {
        $classNameParts = explode('\\', get_class($this));
        
        foreach ($classNameParts as $classNamePart) {
            if (preg_match('/^On[A-Za-z]+$/', $classNamePart)) {
                return $classNamePart;
            }
        }
        
        return null;
    }
    
    protected function callListener() {
        $methodName = $this->getListenerMethodName();
        $this->getListener()->$methodName($this->getEvent());
    }
    
    
    /**
     * 
     * @return \Symfony\Component\HttpKernel\Event\GetResponseEvent
     */
    protected function getEvent() {
        if (is_null($this->event)) {
            $this->event = $this->buildEvent();
        }
        
        return $this->event;
    }
    

    /**
     * 
     * @return \Symfony\Component\HttpKernel\Event\GetResponseEvent
     */    
    protected function buildEvent() {
        $request = new \Symfony\Component\HttpFoundation\Request();
        $request->attributes->set('_controller', self::REQUEST_CONTROLLER);
        
        return new \Symfony\Component\HttpKernel\Event\GetResponseEvent(
            $this->container->get('kernel'),
            $request,
            \Symfony\Component\HttpKernel\HttpKernelInterface::MASTER_REQUEST
        );        
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\EventListener\RequestListener
     */
    protected function getListener() {
        return $this->container->get('simplytestable.controller.action_listener');
    }
    
}