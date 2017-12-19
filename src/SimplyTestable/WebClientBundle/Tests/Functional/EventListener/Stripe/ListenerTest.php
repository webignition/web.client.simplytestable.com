<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

use SimplyTestable\WebClientBundle\Tests\Functional\BaseTestCase;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class ListenerTest extends BaseTestCase {
    
    const EVENT_USER = 'user@example.com';
    
    abstract protected function getEventName();
    abstract protected function getListenerMethodName();    
    
    protected function callListener($data) {
        $data['event'] = $this->getEventName();
        $data['user'] = self::EVENT_USER;        
        
        $methodName = $this->getListenerMethodName();
        $this->getListener()->$methodName(new StripeEvent(new ParameterBag($data)));
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\EventListener\Stripe\Listener
     */
    protected function getListener() {
        return $this->container->get('simplytestable.listener.stripeEvent');
    }

    
}