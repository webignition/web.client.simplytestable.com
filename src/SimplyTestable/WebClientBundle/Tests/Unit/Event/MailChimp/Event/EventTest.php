<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Event\MailChimp\Event;

use SimplyTestable\WebClientBundle\Tests\Unit\BaseTestCase;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class EventTest extends \PHPUnit_Framework_TestCase {

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