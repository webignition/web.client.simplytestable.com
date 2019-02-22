<?php

namespace App\Tests\Unit\Event\MailChimp\Event;

class GetFiredAtTest extends EventTest
{
    private $firedAt = '2009-03-26 21:31:21';

    protected function getEventPostData()
    {
        return array(
            'type' => 'foo',
            'fired_at' => $this->firedAt
        );
    }

    public function testGetType()
    {
        $this->assertEquals($this->firedAt, $this->event->getFiredAt()->format('Y-m-d H:i:s'));
    }
}
