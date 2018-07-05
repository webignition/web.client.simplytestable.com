<?php

namespace Tests\WebClientBundle\Unit\Event\MailChimp\Event;

use Tests\WebClientBundle\Unit\BaseTestCase;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class EventTest extends \PHPUnit\Framework\TestCase {

    /**
     *
     * @var \SimplyTestable\WebClientBundle\Event\MailChimp\Event
     */
    protected $event;

    protected function setUp() {
        $this->event = new MailChimpEvent(new ParameterBag($this->getEventPostData()));
    }

    abstract protected function getEventPostData();
}