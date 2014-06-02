<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Action;

class UnfinishedTest extends RedirectTest {

    protected function getExpectedResponseLocation() {
        return 'http://localhost/http://example.com//1/progress/';
    }


}