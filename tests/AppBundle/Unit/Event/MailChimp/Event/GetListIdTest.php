<?php

namespace Tests\AppBundle\Unit\Event\MailChimp\Event;

class GetListIdTest extends EventTest {

    private $listId = 'bar';

    protected function getEventPostData() {
        return array(
            'type' => 'foo',
            'data' => array(
                'list_id' => $this->listId
            )
        );
    }


    public function testGetListId() {
        $this->assertEquals($this->listId, $this->event->getListId());
    }

}