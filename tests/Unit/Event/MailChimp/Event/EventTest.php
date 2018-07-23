<?php

namespace App\Tests\Unit\Event\MailChimp\Event;

use App\Event\MailChimp\Event as MailChimpEvent;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class EventTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var \App\Event\MailChimp\Event
     */
    protected $event;

    protected function setUp()
    {
        $this->event = new MailChimpEvent(new ParameterBag($this->getEventPostData()));
    }

    abstract protected function getEventPostData();
}
