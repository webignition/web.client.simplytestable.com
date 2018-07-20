<?php

namespace Tests\AppBundle\Unit\Event\MailChimp\Event;

use AppBundle\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class EventTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \AppBundle\Event\MailChimp\Event
     */
    protected $event;

    protected function setUp()
    {
        $this->event = new MailChimpEvent(new ParameterBag($this->getEventPostData()));
    }

    abstract protected function getEventPostData();
}