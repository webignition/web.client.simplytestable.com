<?php

namespace SimplyTestable\WebClientBundle\Tests\Event\MailChimp\Event;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class EventTest extends BaseTestCase {   
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Event\MailChimp\Event
     */
    protected $event;
    
    public function setUp() {
        $this->event = new MailChimpEvent(new ParameterBag($this->getEventPostData()));
    }
    
    abstract protected function getEventPostData();    
}