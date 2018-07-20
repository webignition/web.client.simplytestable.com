<?php

namespace Tests\AppBundle\Unit\Event\MailChimp\Event;

class GetDataTest extends EventTest {

    private $data = array(
        'key' => 'value'
    );

    protected function getEventPostData() {
        return array(
            'type' => 'foo',
            'data' => $this->data
        );
    }


    public function testGetData() {
        $this->assertEquals($this->data, $this->event->getData());
    }

}